<?php

namespace App\Livewire;

use App\Models\JurnalAdmin;
use Livewire\Component;

class JurnalAdmin extends Component
{
    public $jurnalAdmin;

    public function mount()
    {
        $this->jurnalAdmin = JurnalAdmin::all();
    }

    public function render()
    {
        return view('livewire.jurnal-admin');
    }
}
