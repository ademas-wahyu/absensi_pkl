<?php

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\JurnalUser;

class JurnalUserInput extends Component
{
    public $jurnal_date, $activity;

    public function render()
    {
        return view('livewire.jurnal-user-input');
    }
    public function submit()
    {
        $this->validate([
            'jurnal_date' => 'required|date',
            'activity' => 'required|string',
        ]);

        JurnalUser::create([
            'user_id' => auth()->id(),
            'jurnal_date' => $this->jurnal_date,
            'activity' => $this->activity,
        ]);

        $this->resetForms();
        Flux::modal('jurnalUserModal')->close();
    }
    private function resetForms()
    {
        $this->jurnal_date = null;
        $this->activity = null;
    }
}
