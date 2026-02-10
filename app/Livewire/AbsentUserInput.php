<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AbsentUserInput extends Component
{
    public $activeTab = 'qr'; // 'qr' atau 'selfie'

    public function render()
    {
        return view('livewire.absent-user-input');
    }

    /**
     * Verify QR Code untuk absensi
     */
    public function verifyQrCode($token)
    {
        // Cek apakah sudah absen hari ini
        $existingAbsent = AbsentUser::where('user_id', auth()->id())
            ->where('absent_date', now()->toDateString())
            ->first();

        if ($existingAbsent) {
            $this->js("alert('Anda sudah absen hari ini!')");
            return;
        }

        $setting = \App\Models\Setting::where('key', 'attendance_token')->first();

        if (!$setting || $setting->value !== $token) {
            $this->js("alert('QR Code tidak valid!');");
            return;
        }

        AbsentUser::create([
            'user_id' => auth()->id(),
            'absent_date' => now()->toDateString(),
            'status' => 'Hadir',
            'reason' => 'berangkat',
            'verification_method' => 'qr_code',
        ]);

        Flux::modal('input-absent-user')->close();
        $this->dispatch('close-scanner');
        session()->flash('message', 'Absensi via QR Berhasil!');
    }

    /**
     * Submit absensi dengan selfie dan lokasi GPS
     */
    public function submitWithSelfie($imageBase64, $latitude, $longitude)
    {
        $user = auth()->user();

        // Cek apakah sudah absen hari ini
        $existingAbsent = AbsentUser::where('user_id', $user->id)
            ->where('absent_date', now()->toDateString())
            ->first();

        if ($existingAbsent) {
            $this->js("alert('Anda sudah absen hari ini!');");
            return;
        }

        // Simpan foto selfie
        $selfiePath = $this->saveSelfieImage($imageBase64, $user->id);

        if (!$selfiePath) {
            $this->js("alert('Gagal menyimpan foto. Silakan coba lagi.');");
            return;
        }

        // Buat record absensi
        AbsentUser::create([
            'user_id' => $user->id,
            'absent_date' => now()->toDateString(),
            'status' => 'Hadir',
            'reason' => 'berangkat via selfie',
            'selfie_path' => $selfiePath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'verification_method' => 'selfie',
        ]);

        Flux::modal('input-absent-user')->close();
        $this->dispatch('close-camera');
        session()->flash('message', 'Absensi via Selfie Berhasil!');
    }

    /**
     * Simpan gambar selfie ke storage
     */
    private function saveSelfieImage($imageBase64, $userId)
    {
        try {
            // Hapus header base64 jika ada
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
            $imageData = base64_decode($imageData);

            if (!$imageData) {
                return null;
            }

            // Generate nama file unik
            $filename = 'selfies/' . $userId . '_' . now()->format('Y-m-d_His') . '.jpg';

            // Simpan ke storage
            Storage::disk('public')->put($filename, $imageData);

            return $filename;
        } catch (\Exception $e) {
            \Log::error('Error saving selfie: ' . $e->getMessage());
            return null;
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
}
