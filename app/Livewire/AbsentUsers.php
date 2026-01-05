<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsentUser;

class AbsentUsers extends Component
{
    public function render()
    {
        $absentUsers = AbsentUser::orderBy('absent_date', 'desc')->get();

        return view('livewire.absent-users', compact('absentUsers'));
    }
}
