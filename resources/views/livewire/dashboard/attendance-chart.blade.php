<?php

use Livewire\Volt\Component;
use App\Models\AbsentUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $labels = [];
    public array $data = [];

    public function mount()
    {
        // Ambil data 7 hari terakhir
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        $attendanceData = AbsentUser::select(DB::raw('DATE(absent_date) as date'), DB::raw('count(*) as total'))
            ->whereBetween('absent_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        // Isi data untuk 7 hari (termasuk hari yang kosong)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $this->labels[] = Carbon::parse($date)->isoFormat('dddd, D MMM');
            $this->data[] = isset($attendanceData[$date]) ? $attendanceData[$date]->total : 0;
        }
    }

    public function with()
    {
        return [
            'labels' => $this->labels,
            'data' => $this->data,
        ];
    }
}; ?>

<div wire:ignore class="relative h-[300px]">
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
                        backgroundColor: '#3526B3',
                        hoverBackgroundColor: '#8615D9',
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#18181b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            displayColors: false,
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
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>