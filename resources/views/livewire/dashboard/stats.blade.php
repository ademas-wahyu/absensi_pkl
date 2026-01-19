<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\AbsentUser;

new class extends Component {
    public int $totalInterns;
    public int $totalMentors;
    public int $totalAttendanceToday;

    public function mount()
    {
        $this->totalInterns = User::role('murid')->count();
        $this->totalMentors = User::role('mentor')->count();
        $this->totalAttendanceToday = AbsentUser::whereDate('absent_date', today())->count();
    }
}; ?>

<div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
    <!-- Card jml anak -->
    <div class="relative overflow-hidden rounded-xl
    border border-neutral-200 dark:border-neutral-700
    before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
    before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
    bg-white dark:bg-neutral-800
    p-4 pl-5 space-y-2 sm:space-y-4
    shadow-md hover:shadow-xl transition-shadow duration-200
    aspect-[16/9] sm:aspect-video">
        <div class="relative">
            <div class="flex items-start gap-4">
                @svg('hugeicons-student', 'w-[60px] h-[60px] text-black dark:text-gray-300')
                <div class="w-[72px] h-[72px] rounded-2xl bg-gray-300 dark:bg-zinc-700
                    flex items-center justify-center
                    text-white text-2xl font-semibold">
                    {{ $totalInterns }}
                </div>
            </div>
            <h1 class="mt-4 text-base sm:text-sm font-semibold dark:text-gray-400">
                Jumlah Anak PKL/Magang
            </h1>
        </div>
        <x-placeholder-pattern class="absolute inset-0 size-full" />
    </div>

    <!-- Card jml mentor -->
    <div class="relative overflow-hidden rounded-xl
    border border-neutral-200 dark:border-neutral-700
    before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
    before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
    bg-white dark:bg-neutral-800
    p-4 pl-5 space-y-2 sm:space-y-4
    shadow-md hover:shadow-xl transition-shadow duration-200
    aspect-[16/9] sm:aspect-video">
        <div class="relative">
            <div class="flex items-start gap-4">
                @svg('hugeicons-mentor', 'w-[60px] h-[60px] text-black dark:text-gray-300')
                <div class="w-[72px] h-[72px] rounded-2xl bg-gray-300 dark:bg-zinc-700
                    flex items-center justify-center
                    text-white text-2xl font-semibold">
                    {{ $totalMentors }}
                </div>
            </div>
            <h1 class="mt-4 text-base sm:text-sm font-semibold dark:text-gray-400">
                Jumlah Mentor Vodeco
            </h1>
        </div>
        <x-placeholder-pattern class="absolute inset-0 size-full" />
    </div>

    <!-- Card Absensi admin -->
    <div class="relative overflow-hidden rounded-xl
    border border-neutral-200 dark:border-neutral-700
    before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
    before:bg-gradient-to-b before:from-[#3526B3] before:to-[#8615D9]
    bg-white dark:bg-neutral-800
    p-4 pl-5 space-y-2 sm:space-y-4
    shadow-md hover:shadow-xl transition-shadow duration-200
    aspect-[16/9] sm:aspect-video">

        <div class="relative">
            <div class="flex items-start gap-4">
                @svg('gmdi-co-present-o', 'w-[60px] h-[60px] text-black dark:text-gray-300')
                <div class="w-[72px] h-[72px] rounded-2xl bg-gray-300 dark:bg-zinc-700
                    flex items-center justify-center
                    text-white text-2xl font-semibold">
                    {{ $totalAttendanceToday }}
                </div>
            </div>
            <h1 class="mt-4 text-base sm:text-sm font-semibold dark:text-gray-400">
                Absensi Anak PKL Hari Ini
            </h1>
        </div>
        <x-placeholder-pattern class="absolute inset-0 size-full" />
    </div>
</div>