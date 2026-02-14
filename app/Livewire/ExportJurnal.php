<?php

namespace App\Livewire;

use App\Models\JurnalUser;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportJurnal extends Component
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
        $filename = 'rekap_jurnal_' . $start . '_' . $end . '.csv';

        return response()->streamDownload(function () use ($isAdmin, $user, $start, $end) {
            $handle = fopen('php://output', 'w');

            // BOM untuk kompatibilitas Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($handle, ['Nama', 'Email', 'Tanggal', 'Aktivitas']);

            $query = JurnalUser::with('user')
                ->whereBetween('jurnal_date', [$start, $end])
                ->orderBy('jurnal_date', 'asc');

            if (!$isAdmin) {
                $query->where('user_id', $user->id);
            }

            // Chunk untuk efisiensi memori
            $query->chunk(200, function ($jurnals) use ($handle) {
                foreach ($jurnals as $jurnal) {
                    fputcsv($handle, [
                        $jurnal->user->name ?? '-',
                        $jurnal->user->email ?? '-',
                        $jurnal->jurnal_date,
                        $jurnal->activity,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        return view('livewire.export-jurnal');
    }
}
