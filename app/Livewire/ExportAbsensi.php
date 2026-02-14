<?php

namespace App\Livewire;

use App\Models\AbsentUser;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportAbsensi extends Component
{
    public $startDate = '';
    public $endDate = '';

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function export(): StreamedResponse
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ], [
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'endDate.required' => 'Tanggal selesai wajib diisi.',
            'endDate.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $start = $this->startDate;
        $end = $this->endDate;
        $filename = 'rekap_absensi_' . $start . '_' . $end . '.csv';

        return response()->streamDownload(function () use ($isAdmin, $user, $start, $end) {
            $handle = fopen('php://output', 'w');

            // BOM untuk kompatibilitas Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($isAdmin) {
                // Admin: detail per murid, dikelompokkan per anak
                $students = \App\Models\User::role('murid')
                    ->with([
                        'absents' => function ($q) use ($start, $end) {
                            $q->whereBetween('absent_date', [$start, $end])
                                ->orderBy('absent_date', 'asc');
                        }
                    ])
                    ->orderBy('name')
                    ->get();

                foreach ($students as $student) {
                    // Header per murid
                    $hadir = $student->absents->filter(fn($a) => strtolower($a->status) === 'hadir')->count();
                    $izin = $student->absents->filter(fn($a) => strtolower($a->status) === 'izin')->count();
                    $sakit = $student->absents->filter(fn($a) => strtolower($a->status) === 'sakit')->count();

                    fputcsv($handle, ['Nama', $student->name, 'Email', $student->email, 'Divisi', $student->divisi ?? '-']);
                    fputcsv($handle, ['Ringkasan', 'Hadir: ' . $hadir, 'Izin: ' . $izin, 'Sakit: ' . $sakit]);
                    fputcsv($handle, ['Tanggal', 'Jam Masuk', 'Status', 'Alasan', 'Metode Verifikasi']);

                    foreach ($student->absents as $absent) {
                        fputcsv($handle, [
                            $absent->absent_date,
                            $absent->created_at ? $absent->created_at->format('H:i') . ' WIB' : '-',
                            ucfirst($absent->status),
                            $absent->reason ?? '-',
                            $absent->verification_method === 'selfie' ? 'Selfie' : 'QR Code',
                        ]);
                    }

                    // Baris kosong pemisah antar murid
                    fputcsv($handle, []);
                }
            } else {
                // Murid: detail per hari
                fputcsv($handle, ['Tanggal', 'Jam Masuk', 'Status', 'Alasan', 'Metode Verifikasi']);

                AbsentUser::where('user_id', $user->id)
                    ->whereBetween('absent_date', [$start, $end])
                    ->orderBy('absent_date', 'asc')
                    ->chunk(200, function ($absents) use ($handle) {
                        foreach ($absents as $absent) {
                            fputcsv($handle, [
                                $absent->absent_date,
                                $absent->created_at ? $absent->created_at->format('H:i') . ' WIB' : '-',
                                ucfirst($absent->status),
                                $absent->reason ?? '-',
                                $absent->verification_method === 'selfie' ? 'Selfie' : 'QR Code',
                            ]);
                        }
                    });
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        return view('livewire.export-absensi');
    }
}
