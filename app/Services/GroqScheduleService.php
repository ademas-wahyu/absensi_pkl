<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqScheduleService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key', '');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /**
     * Generate jadwal berdasarkan prompt admin.
     * TIDAK mengirim data pribadi siswa — hanya jumlah, hari, dan parameter umum.
     */
    public function generateSchedule(string $prompt, int $studentCount, int $month, int $year, array $weekdaysByWeek): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $daysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
        $monthName = \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y');

        // Bangun deskripsi minggu untuk konteks AI
        $weekDescriptions = [];
        foreach ($weekdaysByWeek as $weekIdx => $dates) {
            $dayNames = [];
            foreach ($dates as $date) {
                $dayNames[] = \Carbon\Carbon::parse($date)->translatedFormat('l d');
            }
            $weekDescriptions[] = 'Minggu ' . ($weekIdx + 1) . ': ' . implode(', ', $dayNames);
        }

        $systemPrompt = <<<PROMPT
Kamu adalah asisten penjadwalan kerja PKL. Tugasmu membuat jadwal bulanan.

ATURAN WAJIB:
1. Tipe jadwal HANYA: "wfo" atau "wfh" (JANGAN PERNAH gunakan "libur", libur diatur admin)
2. Hari Minggu TIDAK termasuk (sudah otomatis libur)
3. Kamu menjadwalkan hari Senin-Sabtu saja
4. Setiap siswa harus punya jadwal yang BERVARIASI (tidak semua sama)
5. Pertimbangkan distribusi yang adil

KONTEKS:
- Bulan: {$monthName}
- Jumlah siswa: {$studentCount}
- Hari kerja per minggu (Senin-Sabtu):
{$this->formatWeekDescriptions($weekDescriptions)}

FORMAT RESPON (WAJIB JSON, tanpa markdown, tanpa penjelasan):
Berikan array of array. Setiap sub-array mewakili 1 siswa.
Setiap elemen di sub-array adalah tipe jadwal ("wfo" atau "wfh") untuk hari kerja berurutan dalam sebulan.

Contoh untuk 2 siswa, 12 hari kerja:
[["wfo","wfh","wfo","wfh","wfo","wfo","wfh","wfo","wfo","wfh","wfo","wfo"],["wfh","wfo","wfh","wfo","wfo","wfh","wfo","wfh","wfh","wfo","wfo","wfh"]]
PROMPT;

        $totalWorkdays = 0;
        foreach ($weekdaysByWeek as $dates) {
            $totalWorkdays += count($dates);
        }

        $userPrompt = "Buatkan jadwal untuk {$studentCount} siswa dengan total {$totalWorkdays} hari kerja per siswa. Instruksi admin: {$prompt}";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $this->model,
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $userPrompt],
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => 4096,
                    ]);

            if (!$response->successful()) {
                return null;
            }

            $content = $response->json('choices.0.message.content', '');

            // Bersihkan markdown jika AI menambahkan ```json ... ```
            $content = preg_replace('/```(?:json)?\s*/i', '', $content);
            $content = trim($content);

            $parsed = json_decode($content, true);

            if (!is_array($parsed) || empty($parsed)) {
                return null;
            }

            return $parsed;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatWeekDescriptions(array $descriptions): string
    {
        return implode("\n", $descriptions);
    }
}
