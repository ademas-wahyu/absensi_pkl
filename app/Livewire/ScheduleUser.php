<?php

namespace App\Livewire;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ScheduleUser extends Component
{
    // Calendar navigation
    public int $currentMonth;

    public int $currentYear;

    public ?string $selectedDate = null;

    public ?Schedule $selectedSchedule = null;

    public function mount(): void
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
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

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedSchedule = Schedule::where('user_id', Auth::id())
            ->where('date', $date)
            ->first();
    }

    public function closeDetail(): void
    {
        $this->selectedDate = null;
        $this->selectedSchedule = null;
    }

    #[Computed]
    public function calendarDays(): array
    {
        $date = \Carbon\Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek;

        $days = [];

        // Add empty cells for days before the first day of the month
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $days[] = null;
        }

        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = \Carbon\Carbon::create($this->currentYear, $this->currentMonth, $day)->format('Y-m-d');
            $days[] = [
                'day' => $day,
                'date' => $dateString,
                'isToday' => $dateString === now()->format('Y-m-d'),
                'isPast' => \Carbon\Carbon::parse($dateString)->isBefore(now()->startOfDay()),
                'isFuture' => \Carbon\Carbon::parse($dateString)->isAfter(now()->endOfDay()),
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
        return Schedule::where('user_id', Auth::id())
            ->whereMonth('date', $this->currentMonth)
            ->whereYear('date', $this->currentYear)
            ->get()
            ->keyBy(function ($schedule) {
                return $schedule->date->format('Y-m-d');
            });
    }

    #[Computed]
    public function todaySchedule(): ?Schedule
    {
        return Schedule::where('user_id', Auth::id())
            ->where('date', now()->format('Y-m-d'))
            ->first();
    }

    #[Computed]
    public function upcomingSchedules()
    {
        return Schedule::where('user_id', Auth::id())
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->limit(7)
            ->get();
    }

    #[Computed]
    public function statistics(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $schedules = Schedule::where('user_id', Auth::id())
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        return [
            'wfo' => $schedules->where('type', 'wfo')->count(),
            'wfh' => $schedules->where('type', 'wfh')->count(),
            'libur' => $schedules->where('type', 'libur')->count(),
            'total' => $schedules->count(),
        ];
    }

    public function getScheduleForDate(string $date): ?Schedule
    {
        return $this->schedules->get($date);
    }

    public function render()
    {
        return view('livewire.schedule-user', [
            'calendarDays' => $this->calendarDays,
            'monthName' => $this->monthName,
            'schedules' => $this->schedules,
            'todaySchedule' => $this->todaySchedule,
            'upcomingSchedules' => $this->upcomingSchedules,
            'statistics' => $this->statistics,
        ]);
    }
}
