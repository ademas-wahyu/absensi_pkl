<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use App\Models\User;
use Livewire\Component;

class AbsentUsers extends Component
{
    public $absentUsers;

    public $students = []; // Untuk admin: list murid dengan absensi mereka

    public $isAdmin = false;

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('admin');

        if ($this->isAdmin) {
            // Admin: ambil semua murid beserta absensi mereka
            $this->students = User::role('murid')
                ->with([
                    'absents' => function ($query) {
                        $query->orderBy('absent_date', 'desc');
                    },
                ])
                ->get();
        } else {
            // Murid: hanya lihat absensi sendiri
            $this->absentUsers = AbsentUser::where('user_id', $user->id)
                ->orderBy('absent_date', 'desc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.absent-users');
    }
}
