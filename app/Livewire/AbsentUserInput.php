<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AbsentUserInput extends Component
{
    public $activeTab = 'qr'; // 'qr' atau 'selfie'
    public bool $isCheckout = false;
    public bool $hasCheckedOut = false;
    public $earlyLeaveReason = '';

    public function mount(): void
    {
        $today = AbsentUser::where('user_id', auth()->id())
            ->where('absent_date', now()->toDateString())
            ->where('status', 'Hadir')
            ->first();

        if ($today) {
            $this->isCheckout = true;
            $this->hasCheckedOut = $today->checkout_at !== null;
        }
    }

    public function render()
    {
        return view('livewire.absent-user-input');
    }

    /**
     * Submit checkout (absen pulang)
     */
    public function submitCheckout()
    {
        $today = AbsentUser::where('user_id', auth()->id())
            ->where('absent_date', now()->toDateString())
            ->where('status', 'Hadir')
            ->first();

        if (!$today) {
            Flux::toast('Anda belum absen masuk hari ini.', variant: 'danger');
            return;
        }

        if ($today->checkout_at) {
            Flux::toast('Anda sudah absen pulang hari ini.', variant: 'danger');
            return;
        }

        // Jika sebelum jam 16:00, wajib isi alasan
        if (now()->hour < 16) {
            $this->validate([
                'earlyLeaveReason' => 'required|string|max:255',
            ], [
                'earlyLeaveReason.required' => 'Alasan pulang cepat wajib diisi karena belum jam 16:00.',
            ]);
        }

        $today->update([
            'checkout_at' => now(),
            'early_leave_reason' => now()->hour < 16 ? $this->earlyLeaveReason : null,
        ]);

        $this->hasCheckedOut = true;
        $this->earlyLeaveReason = '';

        Flux::modal('input-absent-user')->close();
        $this->dispatch('absent-created');
        session()->flash('message', 'Absen pulang berhasil!');
        $this->redirect(route('absent_users'), navigate: true);
    }

    /**
     * Verify QR Code untuk absensi masuk
     */
    public function verifyQrCode($token)
    {
        $existingAbsent = AbsentUser::where('user_id', auth()->id())
            ->where('absent_date', now()->toDateString())
            ->first();

        if ($existingAbsent) {
            $this->js("alert('Anda sudah absen hari ini!')");
            return;
        }

        $envToken = config('services.wfo.qr_token');

        $normalizedInput = trim(urldecode($token));
        $normalizedEnv = trim($envToken ?? '');

        if (!$normalizedEnv || !hash_equals($normalizedEnv, $normalizedInput)) {
            $this->js("alert('QR Code tidak valid! Pastikan Anda scan QR yang terbaru dari halaman admin.');");
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
        $this->dispatch('absent-created');
        session()->flash('message', 'Absensi via QR Berhasil!');
    }

    /**
     * Submit absensi masuk dengan selfie dan lokasi GPS
     */
    public function submitWithSelfie($imageBase64, $latitude, $longitude)
    {
        $user = auth()->user();

        $existingAbsent = AbsentUser::where('user_id', $user->id)
            ->where('absent_date', now()->toDateString())
            ->first();

        if ($existingAbsent) {
            $this->js("alert('Anda sudah absen hari ini!');");
            return;
        }

        $selfiePath = $this->saveSelfieImage($imageBase64, $user->id);

        if (!$selfiePath) {
            $this->js("alert('Gagal menyimpan foto. Silakan coba lagi.');");
            return;
        }

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
        $this->dispatch('absent-created');
        session()->flash('message', 'Absensi via Selfie Berhasil!');
    }

    /**
     * Simpan gambar selfie ke storage
     */
    private function saveSelfieImage($imageBase64, $userId)
    {
        try {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
            $decodedData = base64_decode($imageData);

            if (!$decodedData) {
                return null;
            }

            if (strlen($decodedData) > 2097152) {
                $this->js("alert('Ukuran file terlalu besar! Maksimal 2MB.');");
                return null;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decodedData);

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $this->js("alert('Format file tidak valid! Harap upload gambar (JPG, PNG, GIF, WEBP).');");
                return null;
            }

            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'jpg'
            };

            $filename = 'selfies/' . $userId . '_' . now()->format('Y-m-d_His') . '.' . $extension;

            Storage::disk('public')->put($filename, $decodedData);

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

