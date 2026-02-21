<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Flux\Flux;
use Livewire\Component;

class JurnalUserInput extends Component
{
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

    public function save()
    {
        $this->validate();

        // Cek duplikasi: maksimal 1 jurnal per hari per murid
        $exists = JurnalUser::where('user_id', auth()->id())
            ->where('jurnal_date', $this->jurnal_date)
            ->exists();

        if ($exists) {
            Flux::toast('Anda sudah mengisi jurnal untuk tanggal ini.', variant: 'danger');
            return;
        }

        JurnalUser::create([
            'user_id' => auth()->id(),
            'jurnal_date' => $this->jurnal_date,
            'activity' => $this->activity,
        ]);

        session()->flash('message', 'Jurnal berhasil disimpan!');
        // Reset form fields
        $this->reset();
        // Close modal
        Flux::modal('input-jurnal-user')->close();
    }

    public function render()
    {
        return view('livewire.jurnal-user-input');
    }


}
