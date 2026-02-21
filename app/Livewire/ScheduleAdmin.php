<?php

namespace App\Livewire;

use App\Models\Schedule;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use App\Services\GroqScheduleService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ScheduleAdmin extends Component
{
    use WithPagination, WithFileUploads;

    // Filter properties
    public string $search = '';

    public string $filterDivisi = '';

    public string $filterSekolah = '';

    public string $filterType = '';

    public ?int $selectedUserId = null;

    // Calendar navigation
    public int $currentMonth;

    public int $currentYear;

    // Form properties
    public ?int $scheduleId = null;

    public int $user_id = 0;

    public string $date = '';

    public string $type = 'wfo';

    public string $notes = '';

    // Bulk schedule properties
    public string $bulkStartDate = '';

    public string $bulkEndDate = '';

    public array $bulkDates = [];

    public string $bulkType = 'wfo';

    public string $bulkNotes = '';

    public string $bulkDivisi = ''; // '' = semua divisi

    // Import property
    public $importFile;

    // Auto-generate properties
    public int $autoWfoDays = 3;

    public int $autoWfhDays = 2;

    public string $autoDivisi = '';

    public int $autoMonth = 0;

    public int $autoYear = 0;

    // AI properties
    public string $aiPrompt = '';

    public bool $aiLoading = false;

    protected function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|in:wfh,wfo,libur',
            'notes' => 'nullable|string|max:500',
        ];
    }

    protected function bulkRules(): array
    {
        return [
            'bulkStartDate' => 'required|date',
            'bulkEndDate' => 'required|date|after_or_equal:bulkStartDate',
            'bulkType' => 'required|in:wfh,wfo,libur',
            'bulkNotes' => 'nullable|string|max:500',
            'bulkDivisi' => 'nullable|string',
        ];
    }

    public function mount(): void
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->autoMonth = $this->currentMonth;
        $this->autoYear = $this->currentYear;
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterDivisi = '';
        $this->filterSekolah = '';
        $this->filterType = '';
        $this->selectedUserId = null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDivisi(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSekolah(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->scheduleId = null;
        $this->user_id = 0;
        $this->date = '';
        $this->type = 'wfo';
        $this->notes = '';
        $this->resetValidation();
    }

    public function resetBulkForm(): void
    {
        $this->bulkStartDate = '';
        $this->bulkEndDate = '';
        $this->bulkDates = [];
        $this->bulkType = 'wfo';
        $this->bulkNotes = '';
        $this->bulkDivisi = '';
        $this->resetValidation(['bulkStartDate', 'bulkEndDate', 'bulkType', 'bulkNotes', 'bulkDivisi']);
    }

    public function resetAutoForm(): void
    {
        $this->autoWfoDays = 3;
        $this->autoWfhDays = 2;
        $this->autoDivisi = '';
        $this->autoMonth = $this->currentMonth;
        $this->autoYear = $this->currentYear;
    }

    // Calendar navigation
    public function previousMonth(): void
    {
        if ($this->currentMonth === 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
    }

    public function nextMonth(): void
    {
        if ($this->currentMonth === 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
    }

    public function goToToday(): void
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    // CRUD Operations
    public function edit(int $id): void
    {
        $schedule = Schedule::findOrFail($id);
        $this->scheduleId = $schedule->id;
        $this->user_id = $schedule->user_id;
        $this->date = $schedule->date->format('Y-m-d');
        $this->type = $schedule->type;
        $this->notes = $schedule->notes ?? '';
        $this->resetValidation();
        Flux::modal('edit-schedule')->show();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'user_id' => $this->user_id,
            'date' => $this->date,
            'type' => $this->type,
            'notes' => $this->notes ?: null,
            'created_by' => auth()->id(),
        ];

        if ($this->scheduleId) {
            $schedule = Schedule::findOrFail($this->scheduleId);
            $schedule->update($data);
            session()->flash('success', 'Jadwal berhasil diperbarui!');
        } else {
            // Use updateOrCreate to handle unique constraint
            Schedule::updateOrCreate(
                ['user_id' => $this->user_id, 'date' => $this->date],
                $data
            );
            session()->flash('success', 'Jadwal berhasil ditambahkan!');
        }

        $this->resetForm();
        Flux::modal('edit-schedule')->close();
    }

    public function delete(int $id): void
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        session()->flash('success', 'Jadwal berhasil dihapus!');
    }

    // Quick schedule from calendar click - siklus cepat: kosong → WFO → WFH → Libur → hapus
    public function quickSchedule(int $userId, string $date): void
    {
        $existing = Schedule::where('user_id', $userId)
            ->where('date', $date)
            ->first();

        if (!$existing) {
            // Belum ada jadwal → buat WFO
            Schedule::create([
                'user_id' => $userId,
                'date' => $date,
                'type' => 'wfo',
                'created_by' => auth()->id(),
            ]);
        } elseif ($existing->type === 'wfo') {
            $existing->update(['type' => 'wfh']);
        } elseif ($existing->type === 'wfh') {
            $existing->update(['type' => 'libur']);
        } else {
            // Libur → hapus jadwal
            $existing->delete();
        }
    }

    // Bulk schedule operations
    public function generateBulkDates(): void
    {
        $this->validate($this->bulkRules());

        $start = \Carbon\Carbon::parse($this->bulkStartDate);
        $end = \Carbon\Carbon::parse($this->bulkEndDate);

        $this->bulkDates = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $this->bulkDates[] = [
                'date' => $current->format('Y-m-d'),
                'day_name' => $current->translatedFormat('l'),
                'formatted' => $current->format('d M Y'),
            ];
            $current->addDay();
        }
    }

    public function saveBulk(): void
    {
        $this->validate($this->bulkRules());

        if (empty($this->bulkDates)) {
            $this->generateBulkDates();
        }

        // Ambil semua user murid berdasarkan divisi yang dipilih
        $usersQuery = User::role('murid')->active();
        if ($this->bulkDivisi !== '') {
            $usersQuery->where('divisi', $this->bulkDivisi);
        }
        $users = $usersQuery->pluck('id');

        if ($users->isEmpty()) {
            session()->flash('error', 'Tidak ada siswa ditemukan untuk divisi yang dipilih.');
            return;
        }

        $created = 0;
        $updated = 0;

        DB::beginTransaction();
        try {
            foreach ($users as $userId) {
                foreach ($this->bulkDates as $dateInfo) {
                    $result = Schedule::updateOrCreate(
                        ['user_id' => $userId, 'date' => $dateInfo['date']],
                        [
                            'type' => $this->bulkType,
                            'notes' => $this->bulkNotes ?: null,
                            'created_by' => auth()->id(),
                        ]
                    );

                    if ($result->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jadwal massal.');
            return;
        }

        $divisiLabel = $this->bulkDivisi ?: 'Semua Divisi';
        $message = "Jadwal untuk {$divisiLabel} ({$users->count()} siswa) berhasil dibuat! {$created} baru, {$updated} diperbarui.";
        session()->flash('success', $message);

        $this->resetBulkForm();
        Flux::modal('bulk-schedule')->close();
    }

    // Auto-generate schedule algorithm
    public function generateAutoSchedule(): void
    {
        $this->validate([
            'autoWfoDays' => 'required|integer|min:0|max:6',
            'autoWfhDays' => 'required|integer|min:0|max:6',
            'autoMonth' => 'required|integer|min:1|max:12',
            'autoYear' => 'required|integer|min:2024',
        ]);

        $totalWorkDays = $this->autoWfoDays + $this->autoWfhDays;
        if ($totalWorkDays > 6) {
            session()->flash('error', 'Total hari WFO + WFH tidak boleh lebih dari 6 hari per minggu.');
            return;
        }

        // Ambil semua user murid (filter divisi jika ada)
        $usersQuery = User::role('murid')->active();
        if ($this->autoDivisi !== '') {
            $usersQuery->where('divisi', $this->autoDivisi);
        }
        $userIds = $usersQuery->pluck('id');

        if ($userIds->isEmpty()) {
            session()->flash('error', 'Tidak ada siswa ditemukan untuk divisi yang dipilih.');
            return;
        }

        // Kumpulkan hari kerja (Senin-Sabtu) di bulan target
        $date = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, 1);
        $daysInMonth = $date->daysInMonth;

        $weekdays = []; // Dikelompokkan per minggu
        $currentWeek = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $d = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, $day);
            if ($d->isSunday()) {
                continue; // skip Minggu
            }
            // Deteksi awal minggu baru (Senin)
            if ($d->isMonday() && !empty($weekdays[$currentWeek])) {
                $currentWeek++;
            }
            $weekdays[$currentWeek][] = $d->format('Y-m-d');
        }

        // Buat pattern jadwal per minggu: N hari WFO lalu N hari WFH, sisanya tanpa jadwal
        $created = 0;
        $updated = 0;

        DB::beginTransaction();
        try {
            foreach ($userIds as $userId) {
                foreach ($weekdays as $weekDates) {
                    // Buat distribusi tipe: WFO dan WFH saja (libur diatur admin manual)
                    $types = array_merge(
                        array_fill(0, min($this->autoWfoDays, count($weekDates)), 'wfo'),
                        array_fill(0, min($this->autoWfhDays, max(0, count($weekDates) - $this->autoWfoDays)), 'wfh'),
                    );
                    // Isi sisa hari dengan WFO (default)
                    while (count($types) < count($weekDates)) {
                        $types[] = 'wfo';
                    }
                    shuffle($types); // Acak urutan agar tiap siswa berbeda

                    foreach ($weekDates as $idx => $dateStr) {
                        $result = Schedule::updateOrCreate(
                            ['user_id' => $userId, 'date' => $dateStr],
                            [
                                'type' => $types[$idx],
                                'created_by' => auth()->id(),
                            ]
                        );

                        $result->wasRecentlyCreated ? $created++ : $updated++;
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat generate jadwal otomatis.');
            return;
        }

        $monthName = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, 1)->translatedFormat('F Y');
        $divisiLabel = $this->autoDivisi ?: 'Semua Divisi';
        $message = "Jadwal {$monthName} untuk {$divisiLabel} ({$userIds->count()} siswa) berhasil digenerate! {$created} baru, {$updated} diperbarui.";
        session()->flash('success', $message);

        $this->resetAutoForm();
        Flux::modal('auto-schedule')->close();
    }

    // AI-powered schedule generation
    public function generateAiSchedule(): void
    {
        $this->validate([
            'aiPrompt' => 'required|string|min:5',
            'autoMonth' => 'required|integer|min:1|max:12',
            'autoYear' => 'required|integer|min:2024',
        ]);

        if (empty(config('services.groq.api_key'))) {
            session()->flash('error', 'GROQ_API_KEY belum diatur di file .env');
            return;
        }

        // Ambil user murid (filter divisi jika ada)
        $usersQuery = User::role('murid')->active();
        if ($this->autoDivisi !== '') {
            $usersQuery->where('divisi', $this->autoDivisi);
        }
        $userIds = $usersQuery->pluck('id');

        if ($userIds->isEmpty()) {
            session()->flash('error', 'Tidak ada siswa ditemukan untuk divisi yang dipilih.');
            return;
        }

        // Kumpulkan hari kerja (Senin-Sabtu) per minggu
        $date = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, 1);
        $daysInMonth = $date->daysInMonth;

        $weekdays = [];
        $allWorkdates = [];
        $currentWeek = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $d = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, $day);
            if ($d->isSunday()) {
                continue;
            }
            if ($d->isMonday() && !empty($weekdays[$currentWeek])) {
                $currentWeek++;
            }
            $weekdays[$currentWeek][] = $d->format('Y-m-d');
            $allWorkdates[] = $d->format('Y-m-d');
        }

        // Panggil AI — TIDAK kirim data pribadi, hanya jumlah siswa & hari
        $this->aiLoading = true;
        $groqService = new GroqScheduleService();
        $aiResult = $groqService->generateSchedule(
            $this->aiPrompt,
            $userIds->count(),
            $this->autoMonth,
            $this->autoYear,
            $weekdays
        );
        $this->aiLoading = false;

        if (!$aiResult) {
            session()->flash('error', 'AI gagal membuat jadwal. Coba ubah instruksi atau gunakan mode manual.');
            return;
        }

        // Simpan hasil AI ke database
        $created = 0;
        $updated = 0;

        DB::beginTransaction();
        try {
            foreach ($userIds->values() as $userIndex => $userId) {
                // Ambil jadwal untuk user ini dari AI, fallback ke random jika kurang
                $userSchedule = $aiResult[$userIndex] ?? $aiResult[0] ?? [];

                foreach ($allWorkdates as $dateIndex => $dateStr) {
                    $type = $userSchedule[$dateIndex] ?? 'wfo';
                    // Validasi: hanya wfo atau wfh (libur diatur admin)
                    if (!in_array($type, ['wfo', 'wfh'])) {
                        $type = 'wfo';
                    }

                    $result = Schedule::updateOrCreate(
                        ['user_id' => $userId, 'date' => $dateStr],
                        [
                            'type' => $type,
                            'created_by' => auth()->id(),
                        ]
                    );

                    $result->wasRecentlyCreated ? $created++ : $updated++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jadwal AI.');
            return;
        }

        $monthName = \Carbon\Carbon::create($this->autoYear, $this->autoMonth, 1)->translatedFormat('F Y');
        $divisiLabel = $this->autoDivisi ?: 'Semua Divisi';
        $message = "🤖 Jadwal AI untuk {$monthName} — {$divisiLabel} ({$userIds->count()} siswa) berhasil! {$created} baru, {$updated} diperbarui.";
        session()->flash('success', $message);

        $this->aiPrompt = '';
        $this->resetAutoForm();
        Flux::modal('auto-schedule')->close();
    }

    // Export/Import Operations
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, ['Email', 'Tanggal (YYYY-MM-DD)', 'Tipe (wfo/wfh/libur)', 'Catatan']);
            // Contoh baris
            fputcsv($file, ['siswa@example.com', now()->format('Y-m-d'), 'wfo', 'Contoh catatan']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'template-import-jadwal.csv', $headers);
    }

    public function exportSchedules()
    {
        $fileName = 'jadwal_pkl_' . $this->monthName . '.csv';

        $schedules = Schedule::whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->with('user')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $callback = function () use ($schedules) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Siswa', 'Email', 'Divisi', 'Tanggal', 'Tipe', 'Catatan']);

            foreach ($schedules as $schedule) {
                fputcsv($file, [
                    $schedule->user->name,
                    $schedule->user->email,
                    $schedule->user->divisi,
                    $schedule->date->format('Y-m-d'),
                    strtoupper($schedule->type),
                    $schedule->notes ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function importSchedules()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt|max:2048', // max 2MB
        ]);

        $filePath = $this->importFile->getRealPath();
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Skip the header row

        $successCount = 0;
        $errorCount = 0;
        $rowNum = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                $rowNum++;

                // Skip empty lines
                if (count(array_filter($row)) === 0)
                    continue;

                $email = $row[0] ?? null;
                $date = $row[1] ?? null;
                $type = strtolower($row[2] ?? '');
                $notes = $row[3] ?? null;

                if (!$email || !$date || !in_array($type, ['wfo', 'wfh', 'libur'])) {
                    $errorCount++;
                    continue;
                }

                $user = User::where('email', $email)->role('murid')->first();
                if (!$user) {
                    $errorCount++;
                    continue; // Skip if user not found or not a murid
                }

                Schedule::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    [
                        'type' => $type,
                        'notes' => $notes ?: null,
                        'created_by' => auth()->id(),
                    ]
                );

                $successCount++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat mengimpor file. Pastikan format sudah benar.');
            return;
        } finally {
            fclose($file);
        }

        $this->importFile = null; // reset
        Flux::modal('import-schedule')->close();

        if ($errorCount > 0) {
            session()->flash('success', "{$successCount} jadwal berhasil diimpor. {$errorCount} baris dilewati karena format tidak valid atau email tidak ditemukan.");
        } else {
            session()->flash('success', "{$successCount} jadwal berhasil diimpor.");
        }
    }

    // Select user to view their schedule
    public function selectUser(int $userId): void
    {
        $this->selectedUserId = $userId;
    }

    public function clearSelectedUser(): void
    {
        $this->selectedUserId = null;
    }

    // Computed properties
    #[Computed]
    public function students()
    {
        return User::role('murid')
            ->active()
            ->with([
                'schedules' => function ($query) {
                    $query->whereMonth('date', $this->currentMonth)
                        ->whereYear('date', $this->currentYear);
                }
            ])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . strtolower($this->search) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where(DB::raw('LOWER(name)'), 'like', $searchTerm)
                        ->orWhere(DB::raw('LOWER(email)'), 'like', $searchTerm);
                });
            })
            ->when($this->filterDivisi, function ($query) {
                $query->where('divisi', $this->filterDivisi);
            })
            ->when($this->filterSekolah, function ($query) {
                $query->where('sekolah', 'like', '%' . $this->filterSekolah . '%');
            })
            ->orderBy('name')
            ->paginate(10);
    }

    #[Computed]
    public function calendarDays(): array
    {
        $date = \Carbon\Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; // 0 = Sunday

        $days = [];

        // Sel kosong sebelum hari pertama bulan
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $days[] = null;
        }

        // Hari-hari dalam bulan
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $carbonDate = \Carbon\Carbon::create($this->currentYear, $this->currentMonth, $day);
            $days[] = [
                'day' => $day,
                'date' => $carbonDate->format('Y-m-d'),
                'isToday' => $carbonDate->isToday(),
                'isSunday' => $carbonDate->isSunday(),
                'dayName' => $carbonDate->translatedFormat('D'),
            ];
        }

        return $days;
    }

    #[Computed]
    public function monthName(): string
    {
        return \Carbon\Carbon::create($this->currentYear, $this->currentMonth, 1)->translatedFormat('F Y');
    }

    #[Computed]
    public function schedules()
    {
        if (!$this->selectedUserId) {
            return collect();
        }

        return Schedule::where('user_id', $this->selectedUserId)
            ->whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->get()
            ->keyBy(function ($schedule) {
                return $schedule->date->format('Y-m-d');
            });
    }

    #[Computed]
    public function divisiOptions()
    {
        return \App\Models\DivisiAdmin::orderBy('nama_divisi')->get();
    }

    #[Computed]
    public function sekolahList()
    {
        return cache()->remember('sekolah_list_murid', 3600, function () {
            return User::role('murid')
                ->active()
                ->whereNotNull('sekolah')
                ->distinct()
                ->orderBy('sekolah')
                ->pluck('sekolah');
        });
    }

    public function getScheduleForDate(string $date): ?Schedule
    {
        return $this->schedules->get($date);
    }

    public function render()
    {
        return view('livewire.schedule-admin', [
            'students' => $this->students,
            'calendarDays' => $this->calendarDays,
            'monthName' => $this->monthName,
            'schedules' => $this->schedules,
            'divisiOptions' => $this->divisiOptions,
            'sekolahList' => $this->sekolahList,
        ]);
    }
}
