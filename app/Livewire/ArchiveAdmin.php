<?php

namespace App\Livewire;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ArchiveAdmin extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'murid'; // Default tab

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);

        $user->update(['is_active' => true]);

        $this->invalidateCache();
        session()->flash('success', 'Akun anak PKL berhasil diaktifkan kembali!');
    }

    public function activateMentor($id)
    {
        $mentor = Mentor::findOrFail($id);
        $mentor->update(['is_active' => true]);

        $this->invalidateCache();
        session()->flash('success', 'Akun mentor berhasil diaktifkan kembali!');
    }

    private function invalidateCache(): void
    {
        cache()->forget('sekolah_list_murid');
        cache()->forget('mentor_list');
        cache()->forget('divisi_options');
    }

    public function render()
    {
        $students = collect();
        $mentors = collect();

        if ($this->activeTab === 'murid') {
            $students = User::role('murid')
                ->inactive() // Mengambil yang non-aktif
                ->with('mentor')
                ->when($this->search, function ($query) {
                    $searchTerm = '%' . strtolower($this->search) . '%';
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where(DB::raw('LOWER(name)'), 'like', $searchTerm)
                            ->orWhere(DB::raw('LOWER(email)'), 'like', $searchTerm)
                            ->orWhere('sekolah', 'like', $searchTerm)
                            ->orWhere('divisi', 'like', $searchTerm);
                    });
                })
                ->orderBy('name')
                ->paginate(10);
        } else {
            $mentors = Mentor::inactive()
                ->with('divisi')
                ->when($this->search, function ($query) {
                    $searchTerm = '%' . strtolower($this->search) . '%';
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where(DB::raw('LOWER(nama_mentor)'), 'like', $searchTerm)
                            ->orWhere(DB::raw('LOWER(email)'), 'like', $searchTerm);
                    });
                })
                ->orderBy('nama_mentor')
                ->paginate(10);
        }

        return view('livewire.archive-admin', [
            'students' => $students,
            'mentors' => $mentors,
            'totalStudents' => User::role('murid')->inactive()->count(),
            'totalMentors' => Mentor::inactive()->count(),
        ]);
    }
}
