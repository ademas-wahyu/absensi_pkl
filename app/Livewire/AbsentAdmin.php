<?php

namespace App\Livewire;

use Livewire\Component;

class AbsentAdmin extends Component
{
    public $absentAdmin;

    public function mount()
    {
        $this->absentAdmin = AbsentAdmin::all();
    }

    public function render()
    {
        return view('livewire.absent-admin');
    }
}
