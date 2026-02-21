<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DivisiUser extends Component
{
    // controls whether the profile modal is visible
    public bool $show = false;

    // Divisi data properties
    public string $divisiName = '';
    public string $description = '';
    public $mentors = [];
    public $students = [];

    /**
     * Mount the component and load user data
     */
    public function mount(): void
    {
        $user = Auth::user();

        if ($user) {
            $this->divisiName = $user->divisi ?? 'Belum ditugaskan';

            if ($this->divisiName !== 'Belum ditugaskan') {
                $divisiModel = \App\Models\DivisiAdmin::where('nama_divisi', $this->divisiName)->first();
                $this->description = $divisiModel ? ($divisiModel->deskripsi ?? 'Deskripsi tidak tersedia') : 'Deskripsi tidak tersedia';

                if ($divisiModel) {
                    $this->mentors = \App\Models\Mentor::where('divisi_id', $divisiModel->id)
                        ->where('is_active', true)
                        ->orderBy('nama_mentor')
                        ->get();
                }

                $this->students = \App\Models\User::role('murid')
                    ->where('divisi', $this->divisiName)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();
            } else {
                $this->description = 'Anda belum memiliki divisi.';
                $this->mentors = collect();
                $this->students = collect();
            }
        }
    }

    public function render()
    {
        return view('livewire.divisi-user');
    }
}
