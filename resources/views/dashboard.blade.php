<x-layouts.app :title="__('Dashboard')">

    <div class="flex h-full w-full flex-1 flex-col gap-4
            bg-neutral-50 dark:dark:bg-zinc-800
            p-4 sm:p-6 lg:p-8">

        <!-- Header -->
        <div class="flex flex-col items-start gap-2 border-b border-secondary p-3 sm:p-4">
            <div class="flex items-center gap-2">
            </div>
            <h2 class="text-base sm:text-xl font-semibold dark:text-gray-300">
                Welcome back, {{ auth()->user()->name }}!
            </h2>

            <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                @role('admin')
                    Panel admin untuk mengelola data PKL/Magang.
                @else
                    Here's a quick overview of your dashboard.
                @endrole
            </p>
        </div>

        {{-- ========== KONTEN ADMIN ========== --}}
        @role('admin')
        <!-- Stats Cards -->
        <livewire:dashboard.stats />

        <!-- Attendance Chart -->
        <livewire:dashboard.attendance-chart />

        <!-- Low Attendance List -->
        <livewire:dashboard.low-attendance />
        @endrole

        {{-- ========== KONTEN MURID ========== --}}
        @role('murid')
        <!-- Cards Murid -->
        <livewire:dashboard.murid-stats />
        @endrole

    </div>

</x-layouts.app>
