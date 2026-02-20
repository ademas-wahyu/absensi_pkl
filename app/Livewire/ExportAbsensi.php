<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ExportAbsensi extends Component
{
    public $startDate = "";
    public $endDate = "";
    public $exportFormat = "csv"; // csv, excel, pdf

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format("Y-m-d");
        $this->endDate = now()->format("Y-m-d");
    }

    public function export()
    {
        $this->validate(
            [
                "startDate" => "required|date",
                "endDate" => "required|date|after_or_equal:startDate",
                "exportFormat" => "required|in:csv,excel,pdf",
            ],
            [
                "startDate.required" => "Tanggal mulai wajib diisi.",
                "endDate.required" => "Tanggal selesai wajib diisi.",
                "endDate.after_or_equal" =>
                    "Tanggal selesai harus sama atau setelah tanggal mulai.",
                "exportFormat.required" => "Format export wajib dipilih.",
                "exportFormat.in" => "Format export tidak valid.",
            ],
        );

        $user = auth()->user();
        $isAdmin = $user->hasRole("admin");
        $start = $this->startDate;
        $end = $this->endDate;

        switch ($this->exportFormat) {
            case "csv":
                return $this->exportCsv($isAdmin, $user, $start, $end);
            case "excel":
                return $this->exportExcel($isAdmin, $user, $start, $end);
            case "pdf":
                return $this->exportPdf($isAdmin, $user, $start, $end);
            default:
                session()->flash("error", "Format export tidak valid.");
        }
    }

    private function exportCsv($isAdmin, $user, $start, $end)
    {
        $filename = "rekap_absensi_" . $start . "_" . $end . ".csv";

        return response()->streamDownload(
            function () use ($isAdmin, $user, $start, $end) {
                $handle = fopen("php://output", "w");

                // BOM untuk kompatibilitas Excel
                fprintf($handle, chr(0xef) . chr(0xbb) . chr(0xbf));

                if ($isAdmin) {
                    $this->writeAdminCsv($handle, $start, $end);
                } else {
                    $this->writeUserCsv($handle, $user, $start, $end);
                }

                fclose($handle);
            },
            $filename,
            [
                "Content-Type" => "text/csv; charset=UTF-8",
            ],
        );
    }

    private function writeAdminCsv($handle, $start, $end)
    {
        $students = User::role("murid")
            ->with([
                "absents" => function ($q) use ($start, $end) {
                    $q->whereBetween("absent_date", [$start, $end])->orderBy(
                        "absent_date",
                        "asc",
                    );
                },
            ])
            ->orderBy("name")
            ->get();

        foreach ($students as $student) {
            $hadir = $student->absents
                ->filter(fn($a) => strtolower($a->status) === "hadir")
                ->count();
            $izin = $student->absents
                ->filter(fn($a) => strtolower($a->status) === "izin")
                ->count();
            $sakit = $student->absents
                ->filter(fn($a) => strtolower($a->status) === "sakit")
                ->count();

            fputcsv($handle, [
                "Nama",
                $student->name,
                "Email",
                $student->email,
                "Divisi",
                $student->divisi ?? "-",
            ]);
            fputcsv($handle, [
                "Ringkasan",
                "Hadir: " . $hadir,
                "Izin: " . $izin,
                "Sakit: " . $sakit,
            ]);
            fputcsv($handle, [
                "Tanggal",
                "Jam Masuk",
                "Status",
                "Alasan",
                "Metode Verifikasi",
            ]);

            foreach ($student->absents as $absent) {
                fputcsv($handle, [
                    $absent->absent_date,
                    $absent->created_at
                        ? $absent->created_at->format("H:i") . " WIB"
                        : "-",
                    ucfirst($absent->status),
                    $absent->reason ?? "-",
                    $absent->verification_method === "selfie"
                        ? "Selfie"
                        : "QR Code",
                ]);
            }

            fputcsv($handle, []);
        }
    }

    private function writeUserCsv($handle, $user, $start, $end)
    {
        fputcsv($handle, [
            "Tanggal",
            "Jam Masuk",
            "Status",
            "Alasan",
            "Metode Verifikasi",
        ]);

        AbsentUser::where("user_id", $user->id)
            ->whereBetween("absent_date", [$start, $end])
            ->orderBy("absent_date", "asc")
            ->chunk(200, function ($absents) use ($handle) {
                foreach ($absents as $absent) {
                    fputcsv($handle, [
                        $absent->absent_date,
                        $absent->created_at
                            ? $absent->created_at->format("H:i") . " WIB"
                            : "-",
                        ucfirst($absent->status),
                        $absent->reason ?? "-",
                        $absent->verification_method === "selfie"
                            ? "Selfie"
                            : "QR Code",
                    ]);
                }
            });
    }

    private function exportExcel($isAdmin, $user, $start, $end)
    {
        if (!class_exists("Maatwebsite\Excel\Facades\Excel")) {
            session()->flash(
                "error",
                "Package Excel belum terinstall. Silakan jalankan: composer require maatwebsite/excel",
            );
            return null;
        }

        $filename = "rekap_absensi_" . $start . "_" . $end . ".xlsx";

        // For now, return CSV with Excel-compatible format
        // In production, you would use Laravel Excel package properly
        session()->flash(
            "info",
            'Export Excel akan tersedia setelah menjalankan: composer require maatwebsite/excel && php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"',
        );

        return $this->exportCsv($isAdmin, $user, $start, $end);
    }

    private function exportPdf($isAdmin, $user, $start, $end)
    {
        if (!class_exists("Barryvdh\DomPDF\Facade\Pdf")) {
            session()->flash(
                "error",
                "Package PDF belum terinstall. Silakan jalankan: composer require barryvdh/laravel-dompdf",
            );
            return null;
        }

        try {
            $data = $this->preparePdfData($isAdmin, $user, $start, $end);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                "exports.absensi-pdf",
                $data,
            );
            $pdf->setPaper("A4", "landscape");

            $filename = "rekap_absensi_" . $start . "_" . $end . ".pdf";

            return response()->streamDownload(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                $filename,
                [
                    "Content-Type" => "application/pdf",
                ],
            );
        } catch (\Exception $e) {
            session()->flash("error", "Gagal membuat PDF: " . $e->getMessage());
            return null;
        }
    }

    private function preparePdfData($isAdmin, $user, $start, $end)
    {
        $data = [
            "startDate" => Carbon::parse($start)->format("d M Y"),
            "endDate" => Carbon::parse($end)->format("d M Y"),
            "generatedAt" => now()->format("d M Y H:i"),
            "generatedBy" => $user->name,
        ];

        if ($isAdmin) {
            $students = User::role("murid")
                ->with([
                    "absents" => function ($q) use ($start, $end) {
                        $q->whereBetween("absent_date", [
                            $start,
                            $end,
                        ])->orderBy("absent_date", "asc");
                    },
                ])
                ->orderBy("name")
                ->get()
                ->map(function ($student) {
                    $hadir = $student->absents
                        ->filter(fn($a) => strtolower($a->status) === "hadir")
                        ->count();
                    $izin = $student->absents
                        ->filter(fn($a) => strtolower($a->status) === "izin")
                        ->count();
                    $sakit = $student->absents
                        ->filter(fn($a) => strtolower($a->status) === "sakit")
                        ->count();
                    $total = $hadir + $izin + $sakit;
                    $attendanceRate =
                        $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                    return [
                        "name" => $student->name,
                        "email" => $student->email,
                        "divisi" => $student->divisi ?? "-",
                        "sekolah" => $student->sekolah ?? "-",
                        "hadir" => $hadir,
                        "izin" => $izin,
                        "sakit" => $sakit,
                        "total" => $total,
                        "attendance_rate" => $attendanceRate,
                        "absents" => $student->absents,
                    ];
                });

            $data["students"] = $students;
            $data["isAdmin"] = true;
        } else {
            $absents = AbsentUser::where("user_id", $user->id)
                ->whereBetween("absent_date", [$start, $end])
                ->orderBy("absent_date", "asc")
                ->get();

            $hadir = $absents
                ->filter(fn($a) => strtolower($a->status) === "hadir")
                ->count();
            $izin = $absents
                ->filter(fn($a) => strtolower($a->status) === "izin")
                ->count();
            $sakit = $absents
                ->filter(fn($a) => strtolower($a->status) === "sakit")
                ->count();
            $total = $hadir + $izin + $sakit;
            $attendanceRate =
                $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            $data["user"] = [
                "name" => $user->name,
                "email" => $user->email,
                "divisi" => $user->divisi ?? "-",
                "hadir" => $hadir,
                "izin" => $izin,
                "sakit" => $sakit,
                "total" => $total,
                "attendance_rate" => $attendanceRate,
            ];
            $data["absents"] = $absents;
            $data["isAdmin"] = false;
        }

        return $data;
    }

    public function render()
    {
        return view("livewire.export-absensi");
    }
}
