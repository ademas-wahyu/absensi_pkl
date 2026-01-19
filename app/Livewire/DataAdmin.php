<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Flux\Flux;

class DataAdmin extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDivisi = '';
    public $filterSekolah = '';

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $divisi = '';
    public $sekolah = '';

    // List divisi yang tersedia
    public $divisiList = [
        'SEO',
        'Project',
        'Technical Support',
        'Media Sosial',
        'Customer Service',
        'Admin Pelunasan',
        'Finance',
    ];

    public $selectedUserId = null; // ID user yang sedang diedit

    // ... (property lainnya sama)

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->selectedUserId,
            'password' => $this->selectedUserId ? 'nullable|min:6' : 'required|min:6',
            'divisi' => 'required',
            'sekolah' => 'required',
        ];
    }

    // ... (resetFilters sama)

    public function resetForm()
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->divisi = '';
        $this->sekolah = '';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->divisi = $user->divisi;
        $this->sekolah = $user->sekolah;

        // Password dikosongkan saat edit
        $this->password = '';

        $this->resetValidation();
        Flux::modal('tambah-anak')->show();
    }

    public function save()
    {
        $this->validate();

        if ($this->selectedUserId) {
            $this->updateUser();
        } else {
            $this->createUser();
        }

        $this->resetForm();
        Flux::modal('tambah-anak')->close();
    }

    public function createUser()
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'divisi' => $this->divisi,
            'sekolah' => $this->sekolah,
        ]);

        $user->assignRole('murid');
        session()->flash('success', 'Anak PKL berhasil ditambahkan!');
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->selectedUserId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'divisi' => $this->divisi,
            'sekolah' => $this->sekolah,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        session()->flash('success', 'Data anak PKL berhasil diperbarui!');
    }

    public function render()
    {
        $students = User::role('murid')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterDivisi, function ($query) {
                $query->where('divisi', $this->filterDivisi);
            })
            ->when($this->filterSekolah, function ($query) {
                $query->where('sekolah', 'like', '%' . $this->filterSekolah . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        // Get unique sekolah for filter dropdown
        $sekolahList = User::role('murid')
            ->whereNotNull('sekolah')
            ->distinct()
            ->pluck('sekolah');

        return view('livewire.data-admin', [
            'students' => $students,
            'sekolahList' => $sekolahList,
        ]);
    }
}
