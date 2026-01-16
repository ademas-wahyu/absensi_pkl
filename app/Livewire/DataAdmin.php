<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Livewire\Component;

class DataAdmin extends Component
{
    public $dataAdmin;

    public function mount()
    {
        $this->dataAdmin = DataAdmin::all();
    }

    public function render()
    {
        return view('livewire.data-admin');
    }
}
