<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\AbsentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public float $threshold = 80;
    public string $search = "";
    public string $sortField = "attendance_rate";
    public string $sortDirection = "asc";
    public string $periodFilter = "month"; // month, semester, all

    protected $queryString = ["threshold", "search", "periodFilter"];

    public function mount()
    {
        $this->threshold = request()->get("threshold", 80);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedThreshold()
    {
        $this->resetPage();
    }

    public function updatedPeriodFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection =
                $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortField = $field;
            $this->sortDirection = "asc";
        }
    }

    public function getStudentsProperty()
    {
        // Determine date range based on period filter
        $startDate = match ($this->periodFilter) {
            "month" => Carbon::now()->startOfMonth(),
            "semester" => Carbon::now()->subMonths(6)->startOfMonth(),
            "all" => Carbon::create(2020, 1, 1),
            default => Carbon::now()->startOfMonth(),
        };
        $endDate = Carbon::now();

        // Get all students with their attendance data
        $query = User::role("murid")
            ->with([
                "absents" => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween("absent_date", [$startDate, $endDate]);
                },
            ])
            ->when($this->search, function ($q) {
                $searchTerm = "%" . strtolower($this->search) . "%";
                $q->where(function ($query) use ($searchTerm) {
                    $query
                        ->where(DB::raw("LOWER(name)"), "like", $searchTerm)
                        ->orWhere(DB::raw("LOWER(email)"), "like", $searchTerm)
                        ->orWhere(
                            DB::raw("LOWER(divisi)"),
                            "like",
                            $searchTerm,
                        );
                });
            })
            ->get();

        // Calculate attendance rate for each student
        $studentsWithRate = $query->map(function ($student) use (
            $startDate,
            $endDate,
        ) {
            $totalDays = $student->absents->count();
            $hadirDays = $student->absents->where("status", "hadir")->count();
            $izinDays = $student->absents->where("status", "izin")->count();
            $sakitDays = $student->absents->where("status", "sakit")->count();

            $attendanceRate =
                $totalDays > 0 ? round(($hadirDays / $totalDays) * 100, 1) : 0;

            $student->total_days = $totalDays;
            $student->hadir_days = $hadirDays;
            $student->izin_days = $izinDays;
            $student->sakit_days = $sakitDays;
            $student->attendance_rate = $attendanceRate;

            return $student;
        });

        // Filter by threshold
        // Show students with attendance rate below threshold, including those with 0% attendance (no data)
        $filteredStudents = $studentsWithRate->filter(function ($student) {
            return $student->attendance_rate < $this->threshold;
        });

        // Sort
        $sortedStudents = $filteredStudents->sortBy([
            [$this->sortField, $this->sortDirection],
        ]);

        // Manual pagination
        $perPage = 10;
        $currentPage = $this->getPage();
        $items = $sortedStudents
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        // Create paginator manually
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $sortedStudents->count(),
            $perPage,
            $currentPage,
            ["path" => request()->url(), "query" => request()->query()],
        );

        return $paginator;
    }

    public function getStatisticsProperty()
    {
        $startDate = match ($this->periodFilter) {
            "month" => Carbon::now()->startOfMonth(),
            "semester" => Carbon::now()->subMonths(6)->startOfMonth(),
            "all" => Carbon::create(2020, 1, 1),
            default => Carbon::now()->startOfMonth(),
        };
        $endDate = Carbon::now();

        $allStudents = User::role("murid")
            ->with([
                "absents" => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween("absent_date", [$startDate, $endDate]);
                },
            ])
            ->get();

        $studentsWithRate = $allStudents->map(function ($student) {
            $totalDays = $student->absents->count();
            $hadirDays = $student->absents->where("status", "hadir")->count();
            $attendanceRate =
                $totalDays > 0 ? round(($hadirDays / $totalDays) * 100, 1) : 0;
            return $attendanceRate;
        });

        $belowThreshold = $studentsWithRate
            ->filter(function ($rate) {
                return $rate < $this->threshold;
            })
            ->count();

        return [
            "total_students" => $allStudents->count(),
            "below_threshold" => $belowThreshold,
            "percentage" =>
                $allStudents->count() > 0
                    ? round(($belowThreshold / $allStudents->count()) * 100, 1)
                    : 0,
            "avg_attendance" =>
                $studentsWithRate->count() > 0
                    ? round($studentsWithRate->avg(), 1)
                    : 0,
        ];
    }
};
?>

<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-linear-to-br from-red-500/10 to-orange-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="17" y1="11" x2="22" y2="11"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    Siswa dengan Kehadiran Rendah
                </h2>
                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">
                    Siswa dengan tingkat kehadiran di bawah threshold
                </p>
            </div>
        </div>
    </div>

    <!-- Filters and Stats Cards -->
    <div class="grid gap-4 grid-cols-1 md:grid-cols-4">
        <!-- Threshold Selector -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4 shadow-md">
            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-2">
                Threshold Kehadiran
            </label>
            <select wire:model.live="threshold" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                <option value="70">70% - Sangat Rendah</option>
                <option value="75">75% - Rendah</option>
                <option value="80" selected>80% - Di Bawah Standar</option>
                <option value="85">85% - Mendekati Standar</option>
                <option value="90">90% - Perlu Perhatian</option>
            </select>
        </div>

        <!-- Period Filter -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4 shadow-md">
            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-2">
                Periode Data
            </label>
            <select wire:model.live="periodFilter" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                <option value="month">Bulan Ini</option>
                <option value="semester">6 Bulan Terakhir</option>
                <option value="all">Semua Waktu</option>
            </select>
        </div>

        <!-- Statistics Cards -->
        <div class="rounded-xl border border-red-200 dark:border-red-900/50 bg-linear-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 p-4 shadow-md">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="17" y1="11" x2="22" y2="11"/>
                </svg>
                <p class="text-xs font-medium text-red-700 dark:text-red-300">
                    Di Bawah Threshold
                </p>
            </div>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                {{ $this->statistics['below_threshold'] }}
                <span class="text-sm font-normal text-neutral-600 dark:text-neutral-400">
                    / {{ $this->statistics['total_students'] }}
                </span>
            </p>
            <p class="text-xs text-neutral-600 dark:text-neutral-400 mt-1">
                {{ $this->statistics['percentage'] }}% dari total siswa
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4 shadow-md">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400">
                    <line x1="12" y1="20" x2="12" y2="10"/>
                    <line x1="18" y1="20" x2="18" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="16"/>
                </svg>
                <p class="text-xs font-medium text-neutral-600 dark:text-neutral-400">
                    Rata-rata Kehadiran
                </p>
            </div>
            <p class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">
                {{ $this->statistics['avg_attendance'] }}%
            </p>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                Keseluruhan siswa
            </p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-4 shadow-md">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama, email, atau divisi..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
            </div>
            <button wire:click="$set('search', '')" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                bg-white dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400
                hover:bg-neutral-50 dark:hover:bg-neutral-600 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Results Info -->
    @if($this->students->count() > 0)
        <div class="flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            <span>Menampilkan {{ $this->students->count() }} siswa dengan kehadiran di bawah {{ $threshold }}%</span>
        </div>
    @endif

    <!-- Students List -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-md overflow-hidden">
        @if($this->students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-linear-to-r from-[#3526B3]/5 to-[#8615D9]/5 dark:from-[#3526B3]/10 dark:to-[#8615D9]/10">
                        <tr>
                            <th wire:click="sortBy('name')" class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-700">
                                <div class="flex items-center gap-2">
                                    Nama
                                    @if($sortField === 'name')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $sortDirection === 'asc' ? 'rotate-180' : '' }}">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Divisi
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Sekolah
                            </th>
                            <th wire:click="sortBy('attendance_rate')" class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-700">
                                <div class="flex items-center justify-center gap-2">
                                    Tingkat Kehadiran
                                    @if($sortField === 'attendance_rate')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $sortDirection === 'asc' ? 'rotate-180' : '' }}">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Hadir
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Izin
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Sakit
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($this->students as $student)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-linear-to-br from-[#3526B3] to-[#8615D9] flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">
                                                {{ $student->name }}
                                            </p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $student->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                        {{ $student->divisi ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $student->sekolah ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full
                                            {{ $student->attendance_rate < 50 ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' :
                                               ($student->attendance_rate < 70 ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' :
                                               'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300') }}">
                                            {{ $student->attendance_rate }}%
                                        </span>
                                        <!-- Progress Bar -->
                                        <div class="w-16 h-1.5 bg-neutral-200 dark:bg-neutral-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-300
                                                {{ $student->attendance_rate < 50 ? 'bg-red-500' :
                                                   ($student->attendance_rate < 70 ? 'bg-orange-500' :
                                                   'bg-yellow-500') }}"
                                                style="width: {{ $student->attendance_rate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        {{ $student->hadir_days }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                                        {{ $student->izin_days }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                        {{ $student->sakit_days }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm font-semibold text-neutral-600 dark:text-neutral-400">
                                        {{ $student->total_days }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">
                {{ $this->students->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-12">
                <div class="p-4 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-2">
                    Tidak Ada Siswa dengan Kehadiran Rendah
                </h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 text-center max-w-md">
                    Semua siswa memiliki tingkat kehadiran di atas {{ $threshold }}% pada periode yang dipilih.
                </p>
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap items-center gap-4 text-xs text-neutral-600 dark:text-neutral-400">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <span>Kritis (&lt;50%)</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-orange-500"></div>
            <span>Sangat Rendah (50-69%)</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
            <span>Rendah (70-{{ $threshold }}%)</span>
        </div>
    </div>
</div>
