<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ArchiveAdmin extends Component
{
    use WithPagination;

    public $search = '';

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
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

    private function invalidateCache(): void
    {
        cache()->forget('sekolah_list_murid');
        cache()->forget('mentor_list');
        cache()->forget('divisi_options');
    }

    public function render()
    {
        $students = User::role('murid')
            ->inactive() // Mengambil yang non-aktif
            ->with('mentor')
            ->when($this->search, function ($query) {
                // Gunakan pencarian case-insensitive
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

        return view('livewire.archive-admin', [
            'students' => $students,
        ]);
    }
}
