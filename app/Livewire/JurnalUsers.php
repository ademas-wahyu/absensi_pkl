<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class JurnalUsers extends Component
{
    use WithPagination;

    public $isAdmin = false;

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('admin');
    }

    public function prepareEdit($id)
    {
        $this->dispatch('edit-jurnal', id: $id);
    }

    public function prepareDelete($id)
    {
        JurnalUser::find($id)?->delete();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->isAdmin) {
            // Admin: ambil semua murid dengan pagination, limit jurnals per user
            $students = User::role('murid')
                ->with([
                    'jurnals' => function ($query) {
                        $query->orderBy('jurnal_date', 'desc')->limit(20);
                    },
                ])
                ->paginate(15);

            return view('livewire.jurnal-users', [
                'students' => $students,
                'jurnalUsers' => null,
            ]);
        } else {
            // Murid: hanya lihat jurnal sendiri dengan pagination
            $jurnalUsers = JurnalUser::where('user_id', $user->id)
                ->orderBy('jurnal_date', 'desc')
                ->paginate(20);

            return view('livewire.jurnal-users', [
                'students' => null,
                'jurnalUsers' => $jurnalUsers,
            ]);
        }
    }
}
