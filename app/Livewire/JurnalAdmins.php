<?php

namespace App\Livewire;

use App\Models\JurnalAdmin;
use Livewire\Component;

class JurnalAdmins extends Component
{
    public $jurnalAdmin;

    public function mount(): void
    {
        $this->jurnalAdmin = JurnalAdmin::all();
    }

    public function render()
    {
        return view('livewire.jurnal-admins');
    }
}
