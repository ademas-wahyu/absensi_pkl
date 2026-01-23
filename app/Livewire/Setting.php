<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DivisiAdmin;
use App\Models\Sekolah;
use App\Models\Mentor;
use Illuminate\Contracts\View\View;

class Setting extends Component
{
    // Properties untuk form input
    public $nama_divisi = "";
    public $deskripsi_divisi = "";

    public $nama_sekolah = "";
    public $alamat_sekolah = "";
    public $no_telepon_sekolah = "";

    public $nama_mentor = "";
    public $email_mentor = "";
    public $no_telepon_mentor = "";
    public $divisi_id_mentor = "";
    public $keahlian_mentor = "";

    // Properties untuk modal state
    public $showDivisiModal = false;
    public $showSekolahModal = false;
    public $showMentorModal = false;

    // Properties untuk edit mode
    public $editDivisiId = null;
    public $editSekolahId = null;
    public $editMentorId = null;

    // DIVISI METHODS
    /**
     * Open divisi modal for creating new divisi
     *
     * @return void
     */
    public function openDivisiModal(): void
    {
        $this->resetDivisiForm();
        $this->showDivisiModal = true;
    }

    /**
     * Close divisi modal
     *
     * @return void
     */
    public function closeDivisiModal(): void
    {
        $this->showDivisiModal = false;
        $this->resetDivisiForm();
    }

    /**
     * Save divisi (create or update)
     *
     * @return void
     */
    public function saveDivisi(): void
    {
        $this->validate([
            "nama_divisi" => "required|string|max:255",
            "deskripsi_divisi" => "nullable|string",
        ]);

        if ($this->editDivisiId) {
            $divisi = DivisiAdmin::query()->find($this->editDivisiId);
            $divisi->update([
                "nama_divisi" => $this->nama_divisi,
                "deskripsi" => $this->deskripsi_divisi,
            ]);
            session()->flash("message", "Divisi berhasil diupdate!");
        } else {
            DivisiAdmin::query()->create([
                "nama_divisi" => $this->nama_divisi,
                "deskripsi" => $this->deskripsi_divisi,
            ]);
            session()->flash("message", "Divisi berhasil ditambahkan!");
        }

        $this->closeDivisiModal();
    }

    /**
     * Edit existing divisi
     *
     * @param int $id
     * @return void
     */
    public function editDivisi($id): void
    {
        $divisi = DivisiAdmin::query()->findOrFail($id);
        $this->editDivisiId = $id;
        $this->nama_divisi = $divisi->nama_divisi;
        $this->deskripsi_divisi = $divisi->deskripsi;
        $this->showDivisiModal = true;
    }

    /**
     * Delete divisi
     *
     * @param int $id
     * @return void
     */
    public function deleteDivisi($id): void
    {
        DivisiAdmin::query()->find($id)->delete();
        session()->flash("message", "Divisi berhasil dihapus!");
    }

    /**
     * Reset divisi form
     *
     * @return void
     */
    private function resetDivisiForm(): void
    {
        $this->editDivisiId = null;
        $this->nama_divisi = "";
        $this->deskripsi_divisi = "";
        $this->resetErrorBag();
    }

    // SEKOLAH METHODS
    /**
     * Open sekolah modal for creating new sekolah
     *
     * @return void
     */
    public function openSekolahModal(): void
    {
        $this->resetSekolahForm();
        $this->showSekolahModal = true;
    }

    /**
     * Close sekolah modal
     *
     * @return void
     */
    public function closeSekolahModal(): void
    {
        $this->showSekolahModal = false;
        $this->resetSekolahForm();
    }

    /**
     * Save sekolah (create or update)
     *
     * @return void
     */
    public function saveSekolah(): void
    {
        $this->validate([
            "nama_sekolah" => "required|string|max:255",
            "alamat_sekolah" => "nullable|string",
            "no_telepon_sekolah" => "nullable|string|max:20",
        ]);

        if ($this->editSekolahId) {
            $sekolah = Sekolah::query()->find($this->editSekolahId);
            $sekolah->update([
                "nama_sekolah" => $this->nama_sekolah,
                "alamat" => $this->alamat_sekolah,
                "no_telepon" => $this->no_telepon_sekolah,
            ]);
            session()->flash("message", "Sekolah berhasil diupdate!");
        } else {
            Sekolah::query()->create([
                "nama_sekolah" => $this->nama_sekolah,
                "alamat" => $this->alamat_sekolah,
                "no_telepon" => $this->no_telepon_sekolah,
            ]);
            session()->flash("message", "Sekolah berhasil ditambahkan!");
        }

        $this->closeSekolahModal();
    }

    /**
     * Edit existing sekolah
     *
     * @param int $id
     * @return void
     */
    public function editSekolah($id): void
    {
        $sekolah = Sekolah::query()->findOrFail($id);
        $this->editSekolahId = $id;
        $this->nama_sekolah = $sekolah->nama_sekolah;
        $this->alamat_sekolah = $sekolah->alamat;
        $this->no_telepon_sekolah = $sekolah->no_telepon;
        $this->showSekolahModal = true;
    }

    /**
     * Delete sekolah
     *
     * @param int $id
     * @return void
     */
    public function deleteSekolah($id): void
    {
        Sekolah::query()->find($id)->delete();
        session()->flash("message", "Sekolah berhasil dihapus!");
    }

    /**
     * Reset sekolah form
     *
     * @return void
     */
    private function resetSekolahForm(): void
    {
        $this->editSekolahId = null;
        $this->nama_sekolah = "";
        $this->alamat_sekolah = "";
        $this->no_telepon_sekolah = "";
        $this->resetErrorBag();
    }

    // MENTOR METHODS
    /**
     * Open mentor modal for creating new mentor
     *
     * @return void
     */
    public function openMentorModal(): void
    {
        $this->resetMentorForm();
        $this->showMentorModal = true;
    }

    /**
     * Close mentor modal
     *
     * @return void
     */
    public function closeMentorModal(): void
    {
        $this->showMentorModal = false;
        $this->resetMentorForm();
    }

    /**
     * Save mentor (create or update)
     *
     * @return void
     */
    public function saveMentor(): void
    {
        $this->validate([
            "nama_mentor" => "required|string|max:255",
            "email_mentor" => "nullable|email|max:255",
            "no_telepon_mentor" => "nullable|string|max:20",
            "divisi_id_mentor" => "required|exists:divisi_admins,id",
            "keahlian_mentor" => "nullable|string",
        ]);

        if ($this->editMentorId) {
            $mentor = Mentor::query()->find($this->editMentorId);
            $mentor->update([
                "nama_mentor" => $this->nama_mentor,
                "email" => $this->email_mentor,
                "no_telepon" => $this->no_telepon_mentor,
                "divisi_id" => $this->divisi_id_mentor,
                "keahlian" => $this->keahlian_mentor,
            ]);
            session()->flash("message", "Mentor berhasil diupdate!");
        } else {
            Mentor::query()->create([
                "nama_mentor" => $this->nama_mentor,
                "email" => $this->email_mentor,
                "no_telepon" => $this->no_telepon_mentor,
                "divisi_id" => $this->divisi_id_mentor,
                "keahlian" => $this->keahlian_mentor,
            ]);
            session()->flash("message", "Mentor berhasil ditambahkan!");
        }

        $this->closeMentorModal();
    }

    /**
     * Edit existing mentor
     *
     * @param int $id
     * @return void
     */
    public function editMentor($id): void
    {
        $mentor = Mentor::query()->findOrFail($id);
        $this->editMentorId = $id;
        $this->nama_mentor = $mentor->nama_mentor;
        $this->email_mentor = $mentor->email;
        $this->no_telepon_mentor = $mentor->no_telepon;
        $this->divisi_id_mentor = $mentor->divisi_id;
        $this->keahlian_mentor = $mentor->keahlian;
        $this->showMentorModal = true;
    }

    /**
     * Delete mentor
     *
     * @param int $id
     * @return void
     */
    public function deleteMentor($id): void
    {
        Mentor::query()->find($id)->delete();
        session()->flash("message", "Mentor berhasil dihapus!");
    }

    /**
     * Reset mentor form
     *
     * @return void
     */
    private function resetMentorForm(): void
    {
        $this->editMentorId = null;
        $this->nama_mentor = "";
        $this->email_mentor = "";
        $this->no_telepon_mentor = "";
        $this->divisi_id_mentor = "";
        $this->keahlian_mentor = "";
        $this->resetErrorBag();
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view("livewire.setting", [
            "divisiList" => DivisiAdmin::query()->latest()->get(),
            "sekolahList" => Sekolah::query()->latest()->get(),
            "mentorList" => Mentor::query()->with("divisi")->latest()->get(),
            "divisiOptions" => DivisiAdmin::all(),
        ]);
    }
}
