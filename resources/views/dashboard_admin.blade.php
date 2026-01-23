<x-layouts.app :title="__('Dashboard Admin')">

    <div class="flex h-full w-full flex-1 flex-col gap-4
            bg-neutral-50 dark:dark:bg-zinc-800
            p-4 sm:p-6 lg:p-8">

        <!-- Header -->
        <div class="flex flex-col items-start gap-2 border-b border-secondary p-3 sm:p-4">
            <h2 class="text-base sm:text-xl font-semibold">
                Welcome back, {{ auth()->user()->name }}!
            </h2>

            <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                Here's a quick overview of your dashboard.
            </p>
        </div>

        <!-- Cards -->
        <livewire:dashboard.stats />
        <!-- Chart Kehadiran PKL -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800 p-4 shadow-md hover:shadow-lg transition-shadow duration-200 mt-4">

            <h2 class="text-sm sm:text-base font-semibold mb-4 text-neutral-800 dark:text-neutral-200">
                Grafik Kehadiran Anak PKL
            </h2>

            <div class="relative h-75">
                <canvas id="pklAttendanceChart"></canvas>
            </div>
        </div>

    </div>



</x-layouts.app>
