<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Livewire\Component;

class AbsentUsers extends Component
{
    public $absentUsers;

    public function mount()
    {
        $this->absentUsers = AbsentUser::all();
    }

    public function render()
    {
        return view('livewire.absent-users');
    }
}
