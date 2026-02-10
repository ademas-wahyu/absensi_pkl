<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class JurnalEdit extends Component
{
    public $jurnalId; // Penting untuk menyimpan ID yang diedit
    public $jurnal_date;
    public $activity;

    protected $rules = [
        'jurnal_date' => 'required|date',
        'activity' => 'required|string|min:5',
    ];

    public function render()
    {
        return view('livewire.jurnal-edit');
    }

    #[On('editJurnal')]
    public function loadJurnal($id = null) // Tambahkan default null untuk menghindari crash jika id kosong
    {
        if (!$id)
            return;

        $jurnal = JurnalUser::findOrFail($id);

        $this->jurnalId = $jurnal->id;
        $this->jurnal_date = $jurnal->jurnal_date;
        $this->activity = $jurnal->activity;

        $this->resetValidation();

        // Memicu modal untuk terbuka (setelah data terisi)
        $this->dispatch('show-edit-modal');
    }


    public function submit()
    {
        $this->validate();

        $jurnal = \App\Models\JurnalUser::findOrFail($this->jurnalId);
        Gate::authorize('update', $jurnal);
        $jurnal->update([
            'jurnal_date' => $this->jurnal_date,
            'activity' => $this->activity,
        ]);

        // Mengirim sinyal ke browser untuk menutup modal
        $this->dispatch('close-edit-modal');

        // Refresh tabel utama agar data terbaru muncul
        $this->dispatch('jurnalUpdated');
    }
}