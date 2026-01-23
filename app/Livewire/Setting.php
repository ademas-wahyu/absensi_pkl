<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DivisiAdmin;
use App\Models\Sekolah;
use App\Models\Mentor;
use Livewire\Attributes\On;

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
    public function openDivisiModal()
    {
        $this->resetDivisiForm();
        $this->showDivisiModal = true;
    }

    public function closeDivisiModal()
    {
        $this->showDivisiModal = false;
        $this->resetDivisiForm();
    }

    public function saveDivisi()
    {
        $this->validate([
            "nama_divisi" => "required|string|max:255",
            "deskripsi_divisi" => "nullable|string",
        ]);

        if ($this->editDivisiId) {
            $divisi = DivisiAdmin::find($this->editDivisiId);
            $divisi->update([
                "nama_divisi" => $this->nama_divisi,
                "deskripsi" => $this->deskripsi_divisi,
            ]);
            session()->flash("message", "Divisi berhasil diupdate!");
        } else {
            DivisiAdmin::create([
                "nama_divisi" => $this->nama_divisi,
                "deskripsi" => $this->deskripsi_divisi,
            ]);
            session()->flash("message", "Divisi berhasil ditambahkan!");
        }

        $this->closeDivisiModal();
    }

    public function editDivisi($id)
    {
        $divisi = DivisiAdmin::findOrFail($id);
        $this->editDivisiId = $id;
        $this->nama_divisi = $divisi->nama_divisi;
        $this->deskripsi_divisi = $divisi->deskripsi;
        $this->showDivisiModal = true;
    }

    public function deleteDivisi($id)
    {
        DivisiAdmin::find($id)->delete();
        session()->flash("message", "Divisi berhasil dihapus!");
    }

    private function resetDivisiForm()
    {
        $this->editDivisiId = null;
        $this->nama_divisi = "";
        $this->deskripsi_divisi = "";
        $this->resetErrorBag();
    }

    // SEKOLAH METHODS
    public function openSekolahModal()
    {
        $this->resetSekolahForm();
        $this->showSekolahModal = true;
    }

    public function closeSekolahModal()
    {
        $this->showSekolahModal = false;
        $this->resetSekolahForm();
    }

    public function saveSekolah()
    {
        $this->validate([
            "nama_sekolah" => "required|string|max:255",
            "alamat_sekolah" => "nullable|string",
            "no_telepon_sekolah" => "nullable|string|max:20",
        ]);

        if ($this->editSekolahId) {
            $sekolah = Sekolah::find($this->editSekolahId);
            $sekolah->update([
                "nama_sekolah" => $this->nama_sekolah,
                "alamat" => $this->alamat_sekolah,
                "no_telepon" => $this->no_telepon_sekolah,
            ]);
            session()->flash("message", "Sekolah berhasil diupdate!");
        } else {
            Sekolah::create([
                "nama_sekolah" => $this->nama_sekolah,
                "alamat" => $this->alamat_sekolah,
                "no_telepon" => $this->no_telepon_sekolah,
            ]);
            session()->flash("message", "Sekolah berhasil ditambahkan!");
        }

        $this->closeSekolahModal();
    }

    public function editSekolah($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $this->editSekolahId = $id;
        $this->nama_sekolah = $sekolah->nama_sekolah;
        $this->alamat_sekolah = $sekolah->alamat;
        $this->no_telepon_sekolah = $sekolah->no_telepon;
        $this->showSekolahModal = true;
    }

    public function deleteSekolah($id)
    {
        Sekolah::find($id)->delete();
        session()->flash("message", "Sekolah berhasil dihapus!");
    }

    private function resetSekolahForm()
    {
        $this->editSekolahId = null;
        $this->nama_sekolah = "";
        $this->alamat_sekolah = "";
        $this->no_telepon_sekolah = "";
        $this->resetErrorBag();
    }

    // MENTOR METHODS
    public function openMentorModal()
    {
        $this->resetMentorForm();
        $this->showMentorModal = true;
    }

    public function closeMentorModal()
    {
        $this->showMentorModal = false;
        $this->resetMentorForm();
    }

    public function saveMentor()
    {
        $this->validate([
            "nama_mentor" => "required|string|max:255",
            "email_mentor" => "nullable|email|max:255",
            "no_telepon_mentor" => "nullable|string|max:20",
            "divisi_id_mentor" => "required|exists:divisi_admins,id",
            "keahlian_mentor" => "nullable|string",
        ]);

        if ($this->editMentorId) {
            $mentor = Mentor::find($this->editMentorId);
            $mentor->update([
                "nama_mentor" => $this->nama_mentor,
                "email" => $this->email_mentor,
                "no_telepon" => $this->no_telepon_mentor,
                "divisi_id" => $this->divisi_id_mentor,
                "keahlian" => $this->keahlian_mentor,
            ]);
            session()->flash("message", "Mentor berhasil diupdate!");
        } else {
            Mentor::create([
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

    public function editMentor($id)
    {
        $mentor = Mentor::findOrFail($id);
        $this->editMentorId = $id;
        $this->nama_mentor = $mentor->nama_mentor;
        $this->email_mentor = $mentor->email;
        $this->no_telepon_mentor = $mentor->no_telepon;
        $this->divisi_id_mentor = $mentor->divisi_id;
        $this->keahlian_mentor = $mentor->keahlian;
        $this->showMentorModal = true;
    }

    public function deleteMentor($id)
    {
        Mentor::find($id)->delete();
        session()->flash("message", "Mentor berhasil dihapus!");
    }

    private function resetMentorForm()
    {
        $this->editMentorId = null;
        $this->nama_mentor = "";
        $this->email_mentor = "";
        $this->no_telepon_mentor = "";
        $this->divisi_id_mentor = "";
        $this->keahlian_mentor = "";
        $this->resetErrorBag();
    }

    public function render()
    {
        return view("livewire.setting", [
            "divisiList" => DivisiAdmin::latest()->get(),
            "sekolahList" => Sekolah::latest()->get(),
            "mentorList" => Mentor::with("divisi")->latest()->get(),
            "divisiOptions" => DivisiAdmin::all(),
        ]);
    }
}
