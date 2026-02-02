<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use App\Models\User;
use Livewire\Component;

class JurnalUsers extends Component
{
    public $jurnalUsers;

    public $students = []; // Untuk admin: list murid dengan jurnal mereka

    public $isAdmin = false;

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('admin');

        if ($this->isAdmin) {
            // Admin: ambil semua murid beserta jurnal mereka
            $this->students = User::role('murid')
                ->with([
                    'jurnals' => function ($query) {
                        $query->orderBy('jurnal_date', 'desc');
                    },
                ])
                ->get();
        } else {
            // Murid: hanya lihat jurnal sendiri
            $this->jurnalUsers = JurnalUser::where('user_id', $user->id)
                ->orderBy('jurnal_date', 'desc')
                ->get();
        }
    }

   public function edit($id)
{
    // Mengirim event 'editJurnal' dengan parameter bernama 'id'
    $this->dispatch('editJurnal', id: $id); 
}

    public function prepareDelete($id)
    {
        JurnalUser::find($id)?->delete();
        $this->mount(); // Refresh data
    }

    public function render()
    {
        return view('livewire.jurnal-users');
    }
}
