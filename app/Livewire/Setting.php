<?php

namespace App\Livewire;

use App\Models\DivisiAdmin;
use App\Models\Mentor;
use App\Models\Sekolah;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Setting extends Component
{
    // Properties untuk form input
    public $nama_divisi = "";

    public $deskripsi_divisi = "";

    public $nama_sekolah = "";

    public $alamat_sekolah = "";

    public $no_telepon_sekolah = "";

    // Properties untuk modal state
    public $showDivisiModal = false;

    public $showSekolahModal = false;

    // Properties untuk edit mode
    public $editDivisiId = null;

    public $editSekolahId = null;

    // DIVISI METHODS
    /**
     * Open divisi modal for creating new divisi
     */
    public function openDivisiModal(): void
    {
        $this->resetDivisiForm();
        $this->showDivisiModal = true;
    }

    /**
     * Close divisi modal
     */
    public function closeDivisiModal(): void
    {
        $this->showDivisiModal = false;
        $this->resetDivisiForm();
    }

    /**
     * Save divisi (create or update)
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
     * @param  int  $id
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
     * @param  int  $id
     */
    public function deleteDivisi($id): void
    {
        DivisiAdmin::query()->find($id)->delete();
        session()->flash("message", "Divisi berhasil dihapus!");
    }

    /**
     * Reset divisi form
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
     */
    public function openSekolahModal(): void
    {
        $this->resetSekolahForm();
        $this->showSekolahModal = true;
    }

    /**
     * Close sekolah modal
     */
    public function closeSekolahModal(): void
    {
        $this->showSekolahModal = false;
        $this->resetSekolahForm();
    }

    /**
     * Save sekolah (create or update)
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
     * @param  int  $id
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
     * @param  int  $id
     */
    public function deleteSekolah($id): void
    {
        Sekolah::query()->find($id)->delete();
        session()->flash("message", "Sekolah berhasil dihapus!");
    }

    /**
     * Reset sekolah form
     */
    private function resetSekolahForm(): void
    {
        $this->editSekolahId = null;
        $this->nama_sekolah = "";
        $this->alamat_sekolah = "";
        $this->no_telepon_sekolah = "";
        $this->resetErrorBag();
    }

    // MENTOR METHODS REMOVED - Moved to MentorAdmin.php

    // QR CODE PROPERTIES
    public $attendance_token = "";

    // LOCATION PROPERTIES
    public $office_latitude = "";
    public $office_longitude = "";
    public $office_radius = "";
    public $location_validation_enabled = false;

    /**
     * Initialize component state
     */
    public function mount(): void
    {
        $this->attendance_token = \App\Models\Setting::get(
            "attendance_token",
            "",
        );

        // Load location settings
        $this->office_latitude = \App\Models\Setting::get(
            "office_latitude",
            "-6.175110",
        );
        $this->office_longitude = \App\Models\Setting::get(
            "office_longitude",
            "106.865039",
        );
        $this->office_radius = \App\Models\Setting::get(
            "office_radius_meters",
            "100",
        );
        $this->location_validation_enabled =
            \App\Models\Setting::get("location_validation_enabled", "true") ===
            "true";
    }

    /**
     * Generate new QR code token for attendance
     */
    public function generateQrCode(): void
    {
        // Prevent regeneration if already exists
        if ($this->attendance_token) {
            return;
        }

        $token = \Illuminate\Support\Str::random(32);

        \App\Models\Setting::set("attendance_token", $token);

        $this->attendance_token = $token;
        session()->flash("message", "QR Code berhasil diperbarui!");
        $this->dispatch("qr-code-generated", token: $this->attendance_token);
    }

    /**
     * Save office location settings
     */
    public function saveLocationSettings(): void
    {
        $this->validate(
            [
                "office_latitude" => "required|numeric|between:-90,90",
                "office_longitude" => "required|numeric|between:-180,180",
                "office_radius" => "required|integer|min:10|max:10000",
            ],
            [
                "office_latitude.required" => "Latitude kantor wajib diisi.",
                "office_latitude.between" =>
                    "Latitude harus antara -90 sampai 90.",
                "office_longitude.required" => "Longitude kantor wajib diisi.",
                "office_longitude.between" =>
                    "Longitude harus antara -180 sampai 180.",
                "office_radius.required" => "Radius wajib diisi.",
                "office_radius.min" => "Radius minimal 10 meter.",
                "office_radius.max" => "Radius maksimal 10.000 meter (10km).",
            ],
        );

        \App\Models\Setting::set(
            "office_latitude",
            (string) $this->office_latitude,
        );
        \App\Models\Setting::set(
            "office_longitude",
            (string) $this->office_longitude,
        );
        \App\Models\Setting::set(
            "office_radius_meters",
            (string) $this->office_radius,
        );
        \App\Models\Setting::set(
            "location_validation_enabled",
            $this->location_validation_enabled ? "true" : "false",
        );

        session()->flash("message", "Pengaturan lokasi berhasil disimpan!");
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        $divisiList = DivisiAdmin::query()->latest()->get();

        return view("livewire.setting", [
            "divisiList" => $divisiList,
            "sekolahList" => Sekolah::query()->latest()->get(),
        ]);
    }
}
