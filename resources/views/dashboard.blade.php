<x-layouts.app :title="__('Dashboard')">
<div class="flex h-full w-full flex-1 flex-col gap-4 
            bg-neutral-50 dark:bg-neutral-900 
            p-4 sm:p-6 lg:p-8">

    <!-- nav -->
    

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
    <!-- Card Absensi -->
<div
    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
           bg-stone-300 dark:bg-neutral-900 p-4 space-y-2 sm:space-y-4
           drop-shadow-sm hover:drop-shadow-sm transition-shadow
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Absensi</h1>
    <p class="text-xs sm:text-sm text-blue-500 dark:text-gray-400 mt-2">Isi Absensi</p>
    <a href="#"
       class="absolute bottom-2 right-3 text-xs sm:text-sm text-blue-700 dark:text-gray-400
              font-semibold hover:text-blue-500 dark:hover:text-gray-200
              transition-colors">
            Lihat
    </a>
    <x-placeholder-pattern class="absolute inset-0 size-full" />
</div>

<!-- Card Jurnal -->
<div
    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
           bg-stone-300 dark:bg-neutral-900 p-4 space-y-2 sm:space-y-4
           drop-shadow-sm hover:drop-shadow-sm transition-shadow
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Jurnal Harian</h1>
    <p class="text-xs sm:text-sm text-blue-500 dark:text-gray-400 mt-2">Hari PKL</p>
    <p class="text-xs sm:text-sm text-blue-500 dark:text-gray-400 mt-2">Jurnal Diisi</p>
 <a href="#"
       class="absolute bottom-2 right-3 text-xs sm:text-sm text-blue-700 dark:text-gray-400
              font-semibold hover:text-blue-500 dark:hover:text-gray-200
              transition-colors">
        Lihat
    </a>
    <x-placeholder-pattern class="absolute inset-0 size-full" />
</div>

<!-- Card Divisi -->
<div
    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700
           bg-stone-300 dark:bg-neutral-900 p-4 space-y-2 sm:space-y-4
           drop-shadow-sm hover:drop-shadow-sm transition-shadow
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Divisi</h1>
    <p class="text-xs sm:text-sm text-blue-500 dark:text-gray-400 mt-2">Dalam Divisi</p>
 <a href="#"
       class="absolute bottom-2 right-3 text-xs sm:text-sm text-blue-700 dark:text-gray-400
              font-semibold hover:text-blue-500 dark:hover:text-gray-200
              transition-colors">
        Lihat
    </a>

    <x-placeholder-pattern class="absolute inset-0 size-full" />
</div>
    </div>

    
    
</x-layouts.app>
