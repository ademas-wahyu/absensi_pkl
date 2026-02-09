<?php

namespace App\Livewire;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

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

    public $mentor_id = '';

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
            'sekolah' => $this->selectedUserId ? 'nullable' : 'required',
            'mentor_id' => 'nullable|exists:mentors,id',
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
        $this->mentor_id = '';
        $this->resetValidation();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterDivisi = '';
        $this->filterSekolah = '';
        $this->resetPage();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // ✅ Authorization check - only admins can edit users
        Gate::authorize('update', $user);
        
        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->divisi = $user->divisi;
        $this->sekolah = $user->sekolah;
        $this->mentor_id = $user->mentor_id;

        // Password dikosongkan saat edit
        $this->password = '';

        $this->resetValidation();
        Flux::modal('tambah-anak')->show();
    }

    /**
     * Get divisi options for dropdown
     */
    public function getDivisiOptions()
    {
        return \App\Models\DivisiAdmin::orderBy('nama_divisi')->get();
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
        // ✅ Authorization check - only admins can create users
        Gate::authorize('create', User::class);
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'divisi' => $this->divisi,
            'sekolah' => $this->sekolah,
            'mentor_id' => $this->mentor_id,
        ]);

        $user->assignRole('murid');
        session()->flash('success', 'Anak PKL berhasil ditambahkan!');
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->selectedUserId);
        
        // ✅ Authorization check - only admins can update users
        Gate::authorize('update', $user);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'divisi' => $this->divisi,
            'sekolah' => $this->sekolah,
            'mentor_id' => $this->mentor_id,
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
            ->with('mentor')
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->when($this->filterDivisi, function ($query) {
                $query->where('divisi', $this->filterDivisi);
            })
            ->when($this->filterSekolah, function ($query) {
                $query->where('sekolah', 'like', '%' . $this->filterSekolah . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        // Get unique sekolah for filter dropdown (cached for 1 hour)
        $sekolahList = cache()->remember('sekolah_list_murid', 3600, function () {
            return User::role('murid')
                ->whereNotNull('sekolah')
                ->distinct()
                ->orderBy('sekolah')
                ->pluck('sekolah');
        });

        // Get mentors for dropdown (cached for 1 hour)
        $mentorList = cache()->remember('mentor_list', 3600, function () {
            return \App\Models\Mentor::orderBy('nama_mentor')->get();
        });

        // Get divisi options for dropdown (cached for 1 hour)
        $divisiOptions = cache()->remember('divisi_options', 3600, function () {
            return $this->getDivisiOptions();
        });

        return view('livewire.data-admin', [
            'students' => $students,
            'sekolahList' => $sekolahList,
            'mentorList' => $mentorList,
            'divisiOptions' => $divisiOptions,
        ]);
    }
}
