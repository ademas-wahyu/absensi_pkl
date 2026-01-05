<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\AbsentUser;

class AbsentUserInput extends Component
{ 
    public $absent_date,$status,$reason;
    public function render()
    {
        return view('livewire.absent-user-input');
    }

    public function submit()
    {
        $this->validate([
            'absent_date' => 'required|date',
            'status' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        AbsentUser::create([
            'user_id' => auth()->id(),
            'absent_date' => $this->absent_date,
            'status' => $this->status,
            'reason' => $this->reason,
        ]);
        $this->resetForms();
        Flux::modal('absentUserModal')->close();
    }
    private function resetForms()
    {
        $this->absent_date = null;
        $this->status = null;
        $this->reason = null;
    }
}
