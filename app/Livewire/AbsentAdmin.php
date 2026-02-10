<?php

namespace App\Livewire;

use App\Models\AbsentAdmin as AbsentAdminModel;
use Livewire\Component;

class AbsentAdmin extends Component
{
    public $absentAdmin;

    public function mount(): void
    {
        $this->absentAdmin = AbsentAdminModel::all();
    }

    public function render()
    {
        return view('livewire.absent-admin');
    }
}
