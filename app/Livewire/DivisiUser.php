<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DivisiUser extends Component
{
    // controls whether the profile modal is visible
    public bool $show = false;

    // User data properties
    public string $name = '';
    public string $email = '';
    public string $divisi = '';
    public string $description = '';

    /**
     * Mount the component and load user data
     */
    public function mount(): void
    {
        $user = Auth::user();
        
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->divisi = $user->divisi ?? 'Belum ditugaskan';
            $this->description = $this->getDivisiDescription($user->divisi);
        }
    }

    /**
     * Get divisi description from DivisiAdmin model
     */
    private function getDivisiDescription(?string $divisiName): string
    {
        if (!$divisiName) {
            return 'Belum ada deskripsi';
        }

        $divisi = \App\Models\DivisiAdmin::where('nama_divisi', $divisiName)->first();
        return $divisi ? $divisi->deskripsi : 'Deskripsi tidak tersedia';
    }

    /**
     * Open profile modal
     */
    public function openProfile(): void
    {
        $this->show = true;
    }

    /**
     * Close profile modal
     */
    public function closeProfile(): void
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.divisi-user');
    }
}
