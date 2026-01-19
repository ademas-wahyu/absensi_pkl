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
                            37
                        </div>
                    </div>
                    <h1 class="mt-4 text-base sm:text-sm font-semibold dark:text-gray-400">
                        Jumlah Anak PKL/Magang
                    </h1>
</div>
                    <x-placeholder-pattern class="absolute inset-0 size-full" />

                </a>

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
                            7
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
                            7
                        </div>
                    </div>
                    <h1 class="mt-4 text-base sm:text-sm font-semibold dark:text-gray-400">
                        Absensi Anak PKL Hari Ini
                    </h1>
                </div>
                    <x-placeholder-pattern class="absolute inset-0 size-full" />
            </div>
        </div>
        <!-- Chart Kehadiran PKL -->
<div class="rounded-xl border border-neutral-200 dark:border-neutral-700
            bg-white dark:bg-neutral-800 p-4 shadow-md hover:shadow-lg transition-shadow duration-200 mt-4">

    <h2 class="text-sm sm:text-base font-semibold mb-4 text-neutral-800 dark:text-neutral-200">
        Grafik Kehadiran Anak PKL
    </h2>

    <div class="relative h-[300px]">
        <canvas id="pklAttendanceChart"></canvas>
    </div>
</div>

    </div>



</x-layouts.app>