<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AbsentUsers extends Component
{
    use WithPagination;

    #[On('absent-created')]
    public function refreshData()
    {
        // This method handles the event and triggers a re-render
    }

    public $isAdmin = false;
    public $search = '';
    public $date = '';
    public $divisi = '';
    public $status = '';

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

        $absentsToday = AbsentUser::whereDate('absent_date', $this->date)
            ->whereHas('user', function ($q) {
                $q->role('murid');
                if ($this->divisi) {
                    $q->where('divisi', $this->divisi);
                }
            })
            ->get();

        $present = $absentsToday->filter(fn($a) => strtolower($a->status) === 'hadir')->count();
        $permission = $absentsToday->filter(fn($a) => in_array(strtolower($a->status), ['izin', 'sakit']))->count();
        $alpha = $totalStudents - ($present + $permission);

        return [
            'total_students' => $totalStudents,
            'present' => $present,
            'permission' => $permission,
            'alpha' => $alpha, // Belum Absen / Tanpa Keterangan
        ];
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

            $students = User::role('murid')
                ->active()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->when($this->divisi, function ($query) {
                    $query->where('divisi', $this->divisi);
                })
                ->when($this->status, function ($query) {
                    if ($this->status === 'alfa') {
                        $query->whereDoesntHave('absents', function ($q) {
                            $q->whereDate('absent_date', $this->date);
                        });
                    } elseif (in_array($this->status, ['hadir', 'izin', 'sakit'])) {
                        $query->whereHas('absents', function ($q) {
                            $q->whereDate('absent_date', $this->date)
                                ->where('status', $this->status);
                        });
                    }
                })
                ->with([
                    'absents' => function ($query) {
                        $query->orderBy('absent_date', 'desc')->limit(20);
                    },
                    // Eager load attendance for the selected date specifically
                    // We can access this via a relation if we define it dynamically or just filter the collection in view
                    // But efficiently, let's load it here. Since we can't easily add a dynamic relation on the fly without a trait
                    // We will rely on the collection filtering in the view for the "Today's Status" display
                    // OR we can use a closure to constraints eager load.
                ])
                ->paginate(15);

            return view('livewire.absent-users', [
                'students' => $students,
                'absentUsers' => null,
                'divisions' => $divisions,
                'stats' => $this->stats,
                'selectedDate' => $this->date,
                'hasCheckedInToday' => false,
                'hasCheckedOut' => false,
            ]);
        } else {
            // Murid: hanya lihat absensi sendiri dengan pagination
            $absentUsers = AbsentUser::where('user_id', $user->id)
                ->orderBy('absent_date', 'desc')
                ->paginate(20);

            // Ambil data absensi hari ini (atau tanggal yang dipilih filter) untuk status card
            $todayAbsent = AbsentUser::where('user_id', $user->id)
                ->where('absent_date', $this->date)
                ->first();

            return view('livewire.absent-users', [
                'students' => null,
                'absentUsers' => $absentUsers,
                'divisions' => [],
                'stats' => [],
                'todayAbsent' => $todayAbsent,
                'selectedDate' => $this->date,
                'hasCheckedInToday' => $todayAbsent && strtolower($todayAbsent->status) === 'hadir',
                'hasCheckedOut' => $todayAbsent && $todayAbsent->checkout_at !== null,
            ]);
        }
    }
}
