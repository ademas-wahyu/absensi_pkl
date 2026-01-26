<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Flux\Flux;
use Livewire\Component;

class AbsentUserInput extends Component
{
    // Properties removed as we only use QR Code which auto-fills data

    public function render()
    {
        return view('livewire.absent-user-input');
    }

    // Submit method removed as we only use QR Code now

    public function verifyQrCode($token)
    {
        $setting = \App\Models\Setting::where('key', 'attendance_token')->first();

        if (!$setting || $setting->value !== $token) {
            $this->js("alert('QR Code tidak valid!');");

            return;
        }

        // Check if already attended today?
        // Optional strictly, but good practice. For now simpler is better as per instructions.

        AbsentUser::create([
            'user_id' => auth()->id(),
            'absent_date' => now()->toDateString(),
            'status' => 'Hadir',
            'reason' => 'berangkat',
        ]);

        $this->resetForms();
        Flux::modal('input-absent-user')->close();

        // Use Flux toast or session flash
        $this->dispatch('close-scanner'); // Tell JS to stop scanning
        session()->flash('message', 'Absensi via QR Berhasil!');
    }

    private function resetForms()
    {
        $this->absent_date = null;
        $this->status = null;
        $this->reason = null;
    }
}
