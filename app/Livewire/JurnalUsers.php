<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class JurnalUsers extends Component
{
    use WithPagination;

    public $isAdmin = false;
    public $search = '';
    public $date = '';
    public $divisi = '';

    public function mount()
    {
        $user = auth()->user();
        $this->isAdmin = $user->hasRole('admin');
        $this->date = now()->format('Y-m-d');
    }

    public function getStatsProperty()
    {
        if (!$this->isAdmin) {
            return [];
        }

        $query = User::role('murid')->active();

        if ($this->divisi) {
            $query->where('divisi', $this->divisi);
        }

        $totalStudents = $query->count();

        // Hitung murid yang sudah mengisi jurnal hari ini
        $studentsWithJurnalToday = User::role('murid')
            ->active()
            ->when($this->divisi, function ($q) {
                $q->where('divisi', $this->divisi);
            })
            ->whereHas('jurnals', function ($q) {
                $q->whereDate('jurnal_date', $this->date);
            })
            ->count();

        // Hitung total jurnal hari ini
        $totalJurnalToday = JurnalUser::whereDate('jurnal_date', $this->date)
            ->when($this->divisi, function ($q) {
                $q->whereHas('user', function ($subQ) {
                    $subQ->where('divisi', $this->divisi);
                });
            })
            ->count();

        // Murid belum mengisi jurnal
        $belumMengisi = $totalStudents - $studentsWithJurnalToday;

        return [
            'total_students' => $totalStudents,
            'sudah_mengisi' => $studentsWithJurnalToday,
            'total_jurnal_today' => $totalJurnalToday,
            'belum_mengisi' => $belumMengisi,
        ];
    }

    public function edit($id)
    {
        // Mengirim event 'editJurnal' dengan parameter bernama 'id'
        $this->dispatch('editJurnal', id: $id);
    }

    public function prepareDelete($id)
    {
        $jurnal = JurnalUser::findOrFail($id);

        // ✅ Authorization check - users can only delete their own journals, admins can delete any
        Gate::authorize('delete', $jurnal);

        $jurnal->delete();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->isAdmin) {
            // Dropdown Divisi
            $divisions = User::role('murid')
                ->active()
                ->whereNotNull('divisi')
                ->distinct()
                ->pluck('divisi');

            // Admin: ambil semua murid dengan pagination, limit jurnals per user
            $students = User::role('murid')
                ->active()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->when($this->divisi, function ($query) {
                    $query->where('divisi', $this->divisi);
                })
                ->with([
                    'jurnals' => function ($query) {
                        $query->orderBy('jurnal_date', 'desc')->limit(20);
                    },
                ])
                ->paginate(15);

            return view('livewire.jurnal-users', [
                'students' => $students,
                'jurnalUsers' => null,
                'divisions' => $divisions,
                'stats' => $this->stats,
                'selectedDate' => $this->date,
            ]);
        } else {
            // Murid: hanya lihat jurnal sendiri dengan pagination
            $jurnalUsers = JurnalUser::where('user_id', $user->id)
                ->orderBy('jurnal_date', 'desc')
                ->paginate(20);

            return view('livewire.jurnal-users', [
                'students' => null,
                'jurnalUsers' => $jurnalUsers,
                'divisions' => [],
                'stats' => [],
                'selectedDate' => $this->date,
            ]);
        }
    }
}
