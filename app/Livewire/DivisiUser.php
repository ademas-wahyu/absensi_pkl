<?php

namespace App\Livewire;

use Livewire\Component;

class DivisiUser extends Component
{
    // controls whether the profile modal is visible
    public bool $show = false;

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
