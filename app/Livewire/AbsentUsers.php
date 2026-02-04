<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AbsentUsers extends Component
{
    use WithPagination;

    public $isAdmin = false;

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('admin');
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->isAdmin) {
            // Admin: ambil semua murid dengan pagination, limit absents per user
            $students = User::role('murid')
                ->with([
                    'absents' => function ($query) {
                        $query->orderBy('absent_date', 'desc')->limit(20);
                    },
                ])
                ->paginate(15);

            return view('livewire.absent-users', [
                'students' => $students,
                'absentUsers' => null,
            ]);
        } else {
            // Murid: hanya lihat absensi sendiri dengan pagination
            $absentUsers = AbsentUser::where('user_id', $user->id)
                ->orderBy('absent_date', 'desc')
                ->paginate(20);

            return view('livewire.absent-users', [
                'students' => null,
                'absentUsers' => $absentUsers,
            ]);
        }
    }
}
