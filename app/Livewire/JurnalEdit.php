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

    protected function rules()
    {
        return [
            'jurnal_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subDays(3)->toDateString(),
            ],
            'activity' => 'required|string|min:20',
        ];
    }

    protected function messages()
    {
        return [
            'jurnal_date.before_or_equal' => 'Tanggal jurnal tidak boleh di masa depan.',
            'jurnal_date.after_or_equal' => 'Tanggal jurnal maksimal 3 hari ke belakang.',
            'activity.min' => 'Aktivitas jurnal minimal 20 karakter.',
        ];
    }

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

        // Cek duplikasi tanggal (kecuali jurnal yang sedang diedit)
        $exists = JurnalUser::where('user_id', auth()->id())
            ->where('jurnal_date', $this->jurnal_date)
            ->where('id', '!=', $this->jurnalId)
            ->exists();

        if ($exists) {
            Flux::toast('Sudah ada jurnal lain untuk tanggal ini.', variant: 'danger');
            return;
        }

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