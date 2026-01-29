<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
class JurnalEdit extends Component
{
     public $jurnal_date;

    public $activity;

    public function render()
    {
        return view('livewire.jurnal-edit');
    }
    #[On('editJurnal')]

    public function editJurnal($jurnal)
    {
        JurnalUser::find($jurnal->id);
        $this->jurnal_date = $jurnal->jurnal_date;
        $this->activity = $jurnal->activity;
        Flux::modal("edit-jurnal")->show;
    }
}