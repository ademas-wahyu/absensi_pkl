<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-sm bg-purple-50 p-4 sm:p-6 dark:bg-pink-900">

    <!-- nav -->
    <nav class="flex items-center justify-between mb-4" aria-label="Breadcrumb">
        
        <!-- Header -->
        <div class="flex flex-col items-start gap-2 border-b border-purple-200 p-3 sm:p-4">
            <h2 class="text-base sm:text-xl font-semibold">
                Welcome back, {{ auth()->user()->name }}!
            </h2>

            <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                Here's a quick overview of your dashboard.
            </p>
        </div>

        <!-- Cards -->
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
            <!-- Absensi -->
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
                       bg-blue-100 p-4 space-y-2 sm:space-y-4
                       drop-shadow-sm hover:drop-shadow-md transition-shadow
                       aspect-[16/9] sm:aspect-video">
                <h1 class="text-base sm:text-lg font-semibold text-blue-700">Absensi</h1>
                <p class="text-xs sm:text-sm text-blue-500">Isi Absensi</p>
                <p class="text-xs sm:text-sm text-blue-700 font-semibold text-right">Lihat</p>

                <x-placeholder-pattern class="absolute inset-0 size-full dark:stroke-neutral-100/20" />
            </div>

            <!-- Jurnal -->
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
                       bg-purple-100 p-4 space-y-2 sm:space-y-4
                       drop-shadow-sm hover:drop-shadow-md transition-shadow
                       aspect-[16/9] sm:aspect-video">
                <h1 class="text-base sm:text-lg font-semibold text-blue-700">Jurnal Harian</h1>
                <p class="text-xs sm:text-sm text-blue-500">Hari PKL</p>
                <p class="text-xs sm:text-sm text-blue-500">Jurnal Diisi</p>
                <p class="text-xs sm:text-sm text-blue-700 font-semibold text-right">Lihat</p>
                <x-placeholder-pattern class="absolute inset-0 size-full dark:stroke-neutral-100/20" />
            </div>

            <!-- Divisi -->
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
                       bg-pink-100 p-4 space-y-2 sm:space-y-4
                       drop-shadow-sm hover:drop-shadow-md transition-shadow
                       aspect-[16/9] sm:aspect-video">
                <h1 class="text-base sm:text-lg font-semibold text-blue-700">Divisi</h1>
                <p class="text-xs sm:text-sm text-blue-500">Dalam Divisi</p>
                <p class="text-xs sm:text-sm text-blue-700 font-semibold text-right p-8">Lihat</p>
                <x-placeholder-pattern class="absolute inset-0 size-full dark:stroke-neutral-100/20" />
            </div>
        </div>

    
    
</x-layouts.app>
