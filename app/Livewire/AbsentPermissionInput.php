<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Flux\Flux;
use Livewire\Component;

class AbsentPermissionInput extends Component
{
    public $reason;
    public $status = 'Izin';

    public function save()
    {
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

        // Refresh the parent component if needed, or just redirect/notify
        // Since the parent component (AbsentUsers) listens to DB changes usually via polling or refresh, 
        // we might need to dispatch an event or just let the page refresh naturally if wire:navigate isn't used for this flow.
        // Assuming AbsentUsers re-renders or we want to show a success message.

        session()->flash('message', 'Izin berhasil dikirim.');
        $this->redirect(route('absent_users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.absent-permission-input');
    }
}
