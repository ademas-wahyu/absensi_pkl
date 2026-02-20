<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\AbsentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new class extends Component {
    // Basic stats
    public int $totalInterns;
    public int $totalMentors;
    public int $totalAttendanceToday;

    // Enhanced stats
    public float $attendanceRateToday;
    public int $hadirToday;
    public int $izinToday;
    public int $sakitToday;

    // Monthly stats
    public float $monthlyAttendanceRate;
    public int $monthlyHadir;
    public int $monthlyIzin;
    public int $monthlySakit;
    public int $monthlyTotal;

    // Comparison
    public float $lastMonthAttendanceRate;
    public float $attendanceTrend;

    public function mount()
    {
        $this->calculateBasicStats();
        $this->calculateTodayStats();
        $this->calculateMonthlyStats();
        $this->calculateComparison();
    }

    private function calculateBasicStats()
    {
        $this->totalInterns = User::query()
            ->whereHas("roles", function ($query) {
                $query->where("name", "murid");
            })
            ->count();

        $this->totalMentors = User::query()
            ->whereHas("roles", function ($query) {
                $query->where("name", "mentor");
            })
            ->count();

        $this->totalAttendanceToday = AbsentUser::query()
            ->whereDate("absent_date", today())
            ->count();
    }

    private function calculateTodayStats()
    {
        $todayStats = AbsentUser::query()
            ->whereDate("absent_date", today())
            ->select("status", DB::raw("count(*) as count"))
            ->groupBy("status")
            ->get()
            ->keyBy("status");

        $this->hadirToday = $todayStats->get("hadir")?->count ?? 0;
        $this->izinToday = $todayStats->get("izin")?->count ?? 0;
        $this->sakitToday = $todayStats->get("sakit")?->count ?? 0;

        $this->attendanceRateToday =
            $this->totalInterns > 0
                ? round(($this->hadirToday / $this->totalInterns) * 100, 1)
                : 0;
    }

    private function calculateMonthlyStats()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyStats = AbsentUser::query()
            ->whereBetween("absent_date", [$startOfMonth, $endOfMonth])
            ->select("status", DB::raw("count(*) as count"))
            ->groupBy("status")
            ->get()
            ->keyBy("status");

        $this->monthlyHadir = $monthlyStats->get("hadir")?->count ?? 0;
        $this->monthlyIzin = $monthlyStats->get("izin")?->count ?? 0;
        $this->monthlySakit = $monthlyStats->get("sakit")?->count ?? 0;
        $this->monthlyTotal =
            $this->monthlyHadir + $this->monthlyIzin + $this->monthlySakit;

        $this->monthlyAttendanceRate =
            $this->monthlyTotal > 0
                ? round(($this->monthlyHadir / $this->monthlyTotal) * 100, 1)
                : 0;
    }

    private function calculateComparison()
    {
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $lastMonthHadir = AbsentUser::query()
            ->whereBetween("absent_date", [$lastMonthStart, $lastMonthEnd])
            ->where("status", "hadir")
            ->count();

        $lastMonthTotal = AbsentUser::query()
            ->whereBetween("absent_date", [$lastMonthStart, $lastMonthEnd])
            ->count();

        $this->lastMonthAttendanceRate =
            $lastMonthTotal > 0
                ? round(($lastMonthHadir / $lastMonthTotal) * 100, 1)
                : 0;

        $this->attendanceTrend =
            $this->monthlyAttendanceRate - $this->lastMonthAttendanceRate;
    }
};
?>

<div class="space-y-4">
    <!-- Row 1: Basic Stats -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
        <!-- Total Anak PKL -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            before:absolute before:left-0 before:top-0 before:h-full before:w-1.5
            before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-[#3526B3]/10 to-[#8615D9]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                            Total Anak PKL
                        </p>
                    </div>
                    <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">
                        {{ $totalInterns }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Mentor -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            before:absolute before:left-0 before:top-0 before:h-full before:w-1.5
            before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-[#3526B3]/10 to-[#8615D9]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                            Total Mentor
                        </p>
                    </div>
                    <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">
                        {{ $totalMentors }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Absensi Hari Ini -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            before:absolute before:left-0 before:top-0 before:h-full before:w-1.5
            before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-[#3526B3]/10 to-[#8615D9]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                            Absensi Hari Ini
                        </p>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">
                            {{ $totalAttendanceToday }}
                        </p>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            / {{ $totalInterns }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Today's Breakdown -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-4">
        <!-- Attendance Rate Today -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                    Tingkat Kehadiran
                </p>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ $attendanceRateToday }}
                </p>
                <p class="text-sm text-neutral-500">%</p>
            </div>
        </div>

        <!-- Hadir -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                    Hadir
                </p>
            </div>
            <p class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
                {{ $hadirToday }}
            </p>
        </div>

        <!-- Izin -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600 dark:text-yellow-400">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                    Izin
                </p>
            </div>
            <p class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
                {{ $izinToday }}
            </p>
        </div>

        <!-- Sakit -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800
            p-5 shadow-md hover:shadow-xl transition-all duration-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                    Sakit
                </p>
            </div>
            <p class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
                {{ $sakitToday }}
            </p>
        </div>
    </div>

    <!-- Row 3: Monthly Stats & Comparison -->
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
        <!-- Monthly Attendance Rate -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-gradient-to-br from-[#3526B3]/5 to-[#8615D9]/5
            dark:from-[#3526B3]/10 dark:to-[#8615D9]/10
            p-6 shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-white dark:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                            Kehadiran Bulan Ini
                        </p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-500">
                            {{ Carbon::now()->format('F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <p class="text-4xl font-bold text-[#3526B3] dark:text-[#8615D9]">
                            {{ $monthlyAttendanceRate }}
                        </p>
                        <p class="text-lg text-neutral-500">%</p>
                    </div>
                    <div class="flex gap-4 text-sm">
                        <div>
                            <span class="text-green-600 dark:text-green-400 font-semibold">{{ $monthlyHadir }}</span>
                            <span class="text-neutral-500 dark:text-neutral-400"> Hadir</span>
                        </div>
                        <div>
                            <span class="text-yellow-600 dark:text-yellow-400 font-semibold">{{ $monthlyIzin }}</span>
                            <span class="text-neutral-500 dark:text-neutral-400"> Izin</span>
                        </div>
                        <div>
                            <span class="text-red-600 dark:text-red-400 font-semibold">{{ $monthlySakit }}</span>
                            <span class="text-neutral-500 dark:text-neutral-400"> Sakit</span>
                        </div>
                    </div>
                </div>

                <!-- Progress Circle -->
                <div class="relative w-20 h-20">
                    <svg class="transform -rotate-90 w-20 h-20">
                        <circle cx="40" cy="40" r="35" stroke="currentColor"
                            class="text-neutral-200 dark:text-neutral-700"
                            stroke-width="6" fill="none"/>
                        <circle cx="40" cy="40" r="35" stroke="url(#gradient)"
                            stroke-width="6" fill="none"
                            stroke-dasharray="{{ 2 * 3.14159 * 35 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 35 * (100 - $monthlyAttendanceRate) / 100 }}"
                            stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#3526B3"/>
                                <stop offset="100%" style="stop-color:#8615D9"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-neutral-700 dark:text-neutral-300">
                            {{ $monthlyTotal }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison with Last Month -->
        <div class="relative overflow-hidden rounded-xl
            border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800
            p-6 shadow-md">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                        Perbandingan Bulan Lalu
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">
                        {{ Carbon::now()->subMonth()->format('F Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <p class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">
                            {{ abs($attendanceTrend) }}%
                        </p>
                        @if($attendanceTrend > 0)
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400">
                                    <line x1="12" y1="19" x2="12" y2="5"/>
                                    <polyline points="5 12 12 5 19 12"/>
                                </svg>
                                <span class="text-xs font-semibold text-green-600 dark:text-green-400">Naik</span>
                            </div>
                        @elseif($attendanceTrend < 0)
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <polyline points="19 12 12 19 5 12"/>
                                </svg>
                                <span class="text-xs font-semibold text-red-600 dark:text-red-400">Turun</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-neutral-100 dark:bg-neutral-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-neutral-600 dark:text-neutral-400">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                <span class="text-xs font-semibold text-neutral-600 dark:text-neutral-400">Stabil</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-4 text-sm text-neutral-600 dark:text-neutral-400">
                        <div>
                            <span>Bulan Lalu: </span>
                            <span class="font-semibold">{{ $lastMonthAttendanceRate }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
