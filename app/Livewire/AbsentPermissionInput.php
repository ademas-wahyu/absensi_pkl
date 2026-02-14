<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Flux\Flux;
use Livewire\Component;

class AbsentPermissionInput extends Component
{
    public $reason;
    public $status = 'Izin';
    public bool $hasCheckedIn = false;

    public function mount(): void
    {
        $this->hasCheckedIn = AbsentUser::where('user_id', auth()->id())
            ->where('absent_date', now()->toDateString())
            ->where('status', 'Hadir')
            ->exists();
    }

    public function save()
    {
        // Cek ulang di backend untuk mencegah bypass
        if (
            AbsentUser::where('user_id', auth()->id())
                ->where('absent_date', now()->toDateString())
                ->where('status', 'Hadir')
                ->exists()
        ) {
            Flux::toast('Anda sudah absen masuk hari ini, tidak bisa input izin.', variant: 'danger');
            return;
        }

        $this->validate([
            'reason' => 'required|string|max:255',
            'status' => 'required|in:Izin,Sakit',
        ]);

        AbsentUser::create([
            'user_id' => auth()->id(),
            'absent_date' => now()->toDateString(),
            'status' => $this->status,
            'reason' => $this->reason,
        ]);

        $this->reason = '';

        Flux::modal('input-permission')->close();
        $this->dispatch('absent-created');

        session()->flash('message', 'Izin berhasil dikirim.');
        $this->redirect(route('absent_users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.absent-permission-input');
    }
}
