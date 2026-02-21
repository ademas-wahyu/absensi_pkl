<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Livewire\Component;
use Livewire\WithPagination;

class PersetujuanJurnal extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $jurnal = JurnalUser::findOrFail($id);

        if ($jurnal->is_pending_edit) {
            $jurnal->jurnal_date = $jurnal->pending_jurnal_date;
            $jurnal->activity = $jurnal->pending_activity;
            $jurnal->is_pending_edit = false;
            $jurnal->pending_jurnal_date = null;
            $jurnal->pending_activity = null;
            $jurnal->save();

            session()->flash('success', 'Edit jurnal berhasil disetujui.');
        }
    }

    public function reject($id)
    {
        $jurnal = JurnalUser::findOrFail($id);

        if ($jurnal->is_pending_edit) {
            $jurnal->is_pending_edit = false;
            $jurnal->pending_jurnal_date = null;
            $jurnal->pending_activity = null;
            $jurnal->save();

            // Using session error to trigger error SweetAlert
            session()->flash('error', 'Edit jurnal ditolak.');
        }
    }

    public function render()
    {
        // Temukan Mentor record berdasarkan email user yang login
        $mentorRecord = \App\Models\Mentor::where('email', auth()->user()->email)->first();
        $mentorId = $mentorRecord ? $mentorRecord->id : -1;

        $pendingJurnals = JurnalUser::with('user')
            ->where('is_pending_edit', true)
            ->whereHas('user', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('livewire.persetujuan-jurnal', [
            'pendingJurnals' => $pendingJurnals,
        ]);
    }
}
