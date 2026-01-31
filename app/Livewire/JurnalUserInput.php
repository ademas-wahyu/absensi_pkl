<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Flux\Flux;
use Livewire\Component;

class JurnalUserInput extends Component
{
    public $jurnal_date;

    public $activity;

    protected function rules()
    {
        return [
            'jurnal_date' => 'required|date',
            'activity' => 'required|string',
        ];
    }

    public function save()
    {
        $this->validate();

        JurnalUser::create([
            'user_id' => auth()->id(),
            'jurnal_date' => $this->jurnal_date,
            'activity' => $this->activity,
        ]);

        // Reset form fields
        $this->reset();
        // Close modal
        Flux::modal('input-jurnal-user')->close();
    }

    public function render()
    {
        return view('livewire.jurnal-user-input');
    }

    
}
