<?php

use Livewire\Volt\Component;
use App\Models\AbsentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public string $viewMode = "weekly"; // weekly or monthly
    public array $labels = [];
    public array $data = [];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedViewMode()
    {
        $this->loadData();
    }

    private function loadData()
    {
        if ($this->viewMode === "weekly") {
            $this->loadWeeklyData();
        } else {
            $this->loadMonthlyData();
        }
    }

    private function loadWeeklyData()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        $attendanceData = AbsentUser::select(
            DB::raw("DATE(absent_date) as date"),
            DB::raw("count(*) as total"),
        )
            ->whereBetween("absent_date", [
                $startDate->format("Y-m-d"),
                $endDate->format("Y-m-d"),
            ])
            ->groupBy("date")
            ->orderBy("date", "ASC")
            ->get()
            ->keyBy("date");

        $this->labels = [];
        $this->data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format("Y-m-d");
            $this->labels[] = Carbon::parse($date)->isoFormat("dddd, D MMM");
            $this->data[] = isset($attendanceData[$date])
                ? $attendanceData[$date]->total
                : 0;
        }
    }

    private function loadMonthlyData()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(29);

        $attendanceData = AbsentUser::select(
            DB::raw("DATE(absent_date) as date"),
            DB::raw("count(*) as total"),
        )
            ->whereBetween("absent_date", [
                $startDate->format("Y-m-d"),
                $endDate->format("Y-m-d"),
            ])
            ->groupBy("date")
            ->orderBy("date", "ASC")
            ->get()
            ->keyBy("date");

        $this->labels = [];
        $this->data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format("Y-m-d");
            $this->labels[] = Carbon::parse($date)->format("d M");
            $this->data[] = isset($attendanceData[$date])
                ? $attendanceData[$date]->total
                : 0;
        }
    }

    public function with()
    {
        return [
            "labels" => $this->labels,
            "data" => $this->data,
        ];
    }
};
?>

<div class="rounded-xl border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">

    <!-- Header with Title and Toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-gradient-to-br from-[#3526B3]/10 to-[#8615D9]/10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    Grafik Kehadiran Anak PKL
                </h2>
                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">
                    @if($viewMode === 'weekly')
                        Data 7 hari terakhir
                    @else
                        Data 30 hari terakhir
                    @endif
                </p>
            </div>
        </div>

        <!-- Toggle Button Group -->
        <div class="flex rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <button
                wire:click="$set('viewMode', 'weekly')"
                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium transition-all duration-200 flex items-center gap-2
                    {{ $viewMode === 'weekly'
                        ? 'bg-gradient-to-r from-[#3526B3] to-[#8615D9] text-white shadow-md'
                        : 'bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="hidden sm:inline">Mingguan</span>
                <span class="sm:hidden">7 Hari</span>
            </button>
            <button
                wire:click="$set('viewMode', 'monthly')"
                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium transition-all duration-200 flex items-center gap-2 border-l border-neutral-200 dark:border-neutral-700
                    {{ $viewMode === 'monthly'
                        ? 'bg-gradient-to-r from-[#3526B3] to-[#8615D9] text-white shadow-md'
                        : 'bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="hidden sm:inline">Bulanan</span>
                <span class="sm:hidden">30 Hari</span>
            </button>
        </div>
    </div>

    <!-- Chart Loading State -->
    <div wire:loading class="flex items-center justify-center py-12">
        <div class="flex flex-col items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-[#3526B3] dark:text-[#8615D9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Memuat data...</p>
        </div>
    </div>

    <!-- Chart Container -->
    <div wire:loading.remove wire:ignore class="relative h-[280px] sm:h-[320px] md:h-[350px]">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="pklAttendanceChart"></canvas>

        <script>
            document.addEventListener('livewire:navigated', () => {
                renderChart();
            });

            // Fallback for initial load
            if (typeof Chart === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.onload = renderChart;
                document.head.appendChild(script);
            } else {
                renderChart();
            }

            function renderChart() {
                const ctx = document.getElementById('pklAttendanceChart');
                if (!ctx) return;

                // Destroy existing chart if any to prevent canvas reuse error
                if (window.myPklChart) {
                    window.myPklChart.destroy();
                }

                window.myPklChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @js($labels),
                        datasets: [{
                            label: 'Jumlah Kehadiran',
                            data: @js($data),
                            backgroundColor: 'rgba(53, 38, 179, 0.8)',
                            hoverBackgroundColor: 'rgba(134, 21, 217, 0.9)',
                            borderRadius: 8,
                            borderSkipped: false,
                            barThickness: 'flex',
                            maxBarThickness: 50,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 750,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(24, 24, 27, 0.95)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                displayColors: false,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 14
                                },
                                callbacks: {
                                    label: function(context) {
                                        return 'Kehadiran: ' + context.parsed.y + ' siswa';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.1)',
                                    drawBorder: false,
                                },
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: 12
                                    },
                                    color: 'rgba(107, 114, 128, 1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    color: 'rgba(107, 114, 128, 1)',
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            }
        </script>
    </div>

    <!-- Summary Stats -->
    <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 mb-1">Total</p>
                <p class="text-base sm:text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    {{ array_sum($data) }}
                </p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 mb-1">Rata-rata</p>
                <p class="text-base sm:text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    {{ count($data) > 0 ? round(array_sum($data) / count($data), 1) : 0 }}
                </p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 mb-1">Tertinggi</p>
                <p class="text-base sm:text-lg font-semibold text-neutral-800 dark:text-neutral-200">
                    {{ count($data) > 0 ? max($data) : 0 }}
                </p>
            </div>
        </div>
    </div>
</div>
