<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    protected $fillable = ["key", "value"];

    /**
     * Ambil nilai setting berdasarkan key dengan caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(
            "setting.{$key}",
            now()->addHours(1),
            function () use ($key, $default) {
                $setting = static::query()->where("key", $key)->first();
                return $setting ? $setting->value : $default;
            },
        );
    }

    /**
     * Set nilai setting.
     */
    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(["key" => $key], ["value" => $value]);
        Cache::forget("setting.{$key}");
    }

    /**
     * Ambil koordinat kantor.
     *
     * @return array<string, mixed>
     */
    public static function getOfficeLocation(): array
    {
        return [
            "latitude" => (float) static::get("office_latitude", "-6.175110"),
            "longitude" => (float) static::get(
                "office_longitude",
                "106.865039",
            ),
            "radius" => (int) static::get("office_radius_meters", 100),
        ];
    }

    /**
     * Cek apakah validasi lokasi diaktifkan.
     */
    public static function isLocationValidationEnabled(): bool
    {
        return static::get("location_validation_enabled", "true") === "true";
    }

    /**
     * Hitung jarak antara dua koordinat dalam meter menggunakan formula Haversine.
     */
    public static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
    ): float {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a =
            sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) *
                cos($lat2Rad) *
                sin($deltaLon / 2) *
                sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validasi apakah lokasi user dalam radius kantor.
     *
     * @return array<string, mixed>
     */
    public static function isWithinOfficeRadius(
        float $userLat,
        float $userLon,
    ): array {
        if (!static::isLocationValidationEnabled()) {
            return [
                "valid" => true,
                "distance" => 0,
                "message" => "Validasi lokasi tidak diaktifkan.",
            ];
        }

        $office = static::getOfficeLocation();
        $distance = static::calculateDistance(
            $office["latitude"],
            $office["longitude"],
            $userLat,
            $userLon,
        );

        $isWithinRadius = $distance <= $office["radius"];

        // Reverse geocoding untuk mendapatkan nama lokasi
        $locationName = static::reverseGeocode($userLat, $userLon);

        return [
            "valid" => $isWithinRadius,
            "distance" => round($distance, 1),
            "radius" => $office["radius"],
            "location_name" => $locationName,
            "message" => $isWithinRadius
                ? "Lokasi valid ({$distance}m dari kantor)."
                : "Lokasi di luar radius! Anda berada di: {$locationName}. Jarak: " .
                    round($distance) .
                    "m, maksimal: {$office["radius"]}m.",
        ];
    }

    /**
     * Reverse geocoding menggunakan Nominatim (OpenStreetMap) - GRATIS
     * Mengembalikan nama lokasi dari koordinat GPS.
     */
    public static function reverseGeocode(float $lat, float $lon): string
    {
        $cacheKey = "geocode_{$lat}_{$lon}";

        return Cache::remember($cacheKey, now()->addDay(), function () use (
            $lat,
            $lon,
        ) {
            try {
                $response = Http::withHeaders([
                    "User-Agent" => "AbsensiPKL-App/1.0",
                ])
                    ->timeout(5)
                    ->get("https://nominatim.openstreetmap.org/reverse", [
                        "format" => "json",
                        "lat" => $lat,
                        "lon" => $lon,
                        "zoom" => 18,
                        "addressdetails" => 1,
                    ]);

                if (!$response->successful()) {
                    return "Koordinat: {$lat}, {$lon}";
                }

                $data = $response->json();

                if (isset($data["display_name"])) {
                    // Ambil bagian penting dari alamat saja
                    $address = $data["address"] ?? [];
                    $parts = [];

                    if (!empty($address["road"])) {
                        $parts[] = $address["road"];
                    }
                    if (!empty($address["suburb"])) {
                        $parts[] = $address["suburb"];
                    }
                    if (!empty($address["city_district"])) {
                        $parts[] = $address["city_district"];
                    }
                    if (!empty($address["city"])) {
                        $parts[] = $address["city"];
                    }

                    return !empty($parts)
                        ? implode(", ", $parts)
                        : $data["display_name"];
                }

                return "Lokasi tidak dikenali";
            } catch (\Exception $e) {
                Log::warning("Reverse geocoding failed: " . $e->getMessage());
                return "Koordinat: {$lat}, {$lon}";
            }
        });
    }
}
