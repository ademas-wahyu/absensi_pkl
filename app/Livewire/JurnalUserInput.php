<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Flux\Flux;
use Livewire\Component;

class JurnalUserInput extends Component
{
    public $jurnal_date;

    public $activity;

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
        Flux::modal('input-jurnal-user')->close();
        $this->dispatch(event: 'reoloadJurnalUsers');
    }

    private function resetForms()
    {
        $this->jurnal_date = null;
        $this->activity = null;
    }
}
