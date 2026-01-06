<?php

namespace App\Livewire;
use App\Models\JurnalUser;
use Livewire\Component;

class JurnalUsers extends Component
{
    public $jurnalUsers;
    public function mount()
    {
        $this->jurnalUsers = JurnalUser::all();
    }
    public function render()
    {
        return view('livewire.jurnal-users');
    }
}
