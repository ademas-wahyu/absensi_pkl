<?php

namespace App\Livewire;

use App\Models\DivisiAdmin;
use App\Models\Mentor;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class MentorAdmin extends Component
{
    use WithPagination;

    public $search = '';

    // Form properties
    public $nama_mentor = '';
    public $email_mentor = '';
    public $no_telepon_mentor = '';
    public $divisi_id_mentor = '';
    public $keahlian_mentor = '';

    // Edit mode
    public $editMentorId = null;
    public $showFormModal = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openFormModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function editMentor($id): void
    {
        $mentor = Mentor::findOrFail($id);
        $this->editMentorId = $id;
        $this->nama_mentor = $mentor->nama_mentor;
        $this->email_mentor = $mentor->email;
        $this->no_telepon_mentor = $mentor->no_telepon;
        $this->divisi_id_mentor = $mentor->divisi_id;
        $this->keahlian_mentor = $mentor->keahlian;
        $this->showFormModal = true;
    }

    public function saveMentor(): void
    {
        $this->validate([
            'nama_mentor' => 'required|string|max:255',
            'email_mentor' => 'nullable|email|max:255',
            'no_telepon_mentor' => 'nullable|string|max:20',
            'divisi_id_mentor' => 'required|exists:divisi_admins,id',
            'keahlian_mentor' => 'nullable|string',
        ]);

        $data = [
            'nama_mentor' => $this->nama_mentor,
            'email' => $this->email_mentor,
            'no_telepon' => $this->no_telepon_mentor,
            'divisi_id' => $this->divisi_id_mentor,
            'keahlian' => $this->keahlian_mentor,
        ];

        if ($this->editMentorId) {
            Mentor::findOrFail($this->editMentorId)->update($data);
            Flux::toast('Mentor berhasil diupdate!', variant: 'success');
        } else {
            Mentor::create($data);
            Flux::toast('Mentor berhasil ditambahkan!', variant: 'success');
        }

        $this->closeFormModal();
    }

    public function toggleActive($id): void
    {
        $mentor = Mentor::findOrFail($id);
        $mentor->update(['is_active' => !$mentor->is_active]);

        $status = $mentor->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Flux::toast("Mentor berhasil {$status}!", variant: 'success');
    }

    public function deleteMentor($id): void
    {
        Mentor::findOrFail($id)->delete();
        Flux::toast('Mentor berhasil dihapus!', variant: 'success');
    }

    private function resetForm(): void
    {
        $this->editMentorId = null;
        $this->nama_mentor = '';
        $this->email_mentor = '';
        $this->no_telepon_mentor = '';
        $this->divisi_id_mentor = '';
        $this->keahlian_mentor = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $mentors = Mentor::query()
            ->with('divisi')
            ->when($this->search, function ($query) {
                $query->where('nama_mentor', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        $divisiOptions = DivisiAdmin::all();

        return view('livewire.mentor-admin', [
            'mentors' => $mentors,
            'divisiOptions' => $divisiOptions,
        ]);
    }
}
