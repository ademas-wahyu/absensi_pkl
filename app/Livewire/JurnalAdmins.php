<?php

namespace App\Livewire;

use Livewire\Component;

class JurnalAdmins extends Component
{
    public $jurnalAdmin;

    public function mount()
    {
        $this->jurnalAdmin = JurnalAdmin::all();
    }

    public function render()
    {
        return view('livewire.jurnal-admins');
    }
}
