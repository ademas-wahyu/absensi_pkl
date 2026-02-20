<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan pengaturan lokasi kantor untuk validasi radius absensi.
     */
    public function up(): void
    {
        // Koordinat default (contoh: Jakarta Pusat)
        // Admin dapat mengubah nilai ini melalui halaman settings
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'office_latitude',
                'value' => '-6.175110', // Default: Jakarta Pusat
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'office_longitude',
                'value' => '106.865039', // Default: Jakarta Pusat
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'office_radius_meters',
                'value' => '100', // Default: 100 meter
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'location_validation_enabled',
                'value' => 'true', // Aktifkan validasi lokasi
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'office_latitude',
            'office_longitude',
            'office_radius_meters',
            'location_validation_enabled',
        ])->delete();
    }
};
