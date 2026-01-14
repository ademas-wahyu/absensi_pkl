<?php

namespace App\Livewire;

use App\Models\JurnalUser;
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
