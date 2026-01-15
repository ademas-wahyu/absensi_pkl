<x-layouts.app :title="__('Dashboard')">

<div class="flex h-full w-full flex-1 flex-col gap-4 
            bg-neutral-50 dark:dark:bg-zinc-800
            p-4 sm:p-6 lg:p-8">

        <!-- Header -->
        <div class="flex flex-col items-start gap-2 border-b border-secondary p-3 sm:p-4">
            <div class="flex items-center gap-2">
                    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                </div>
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
    class="relative overflow-hidden rounded-xl border-l-8 border-blue-500 border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 p-4 space-y-2 sm:space-y-4
           shadow-md hover:shadow-xl transition-shadow duration-200
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Absensi</h1>
    <svg xmlns="http://www.w3.org/2000/svg"
         class="absolute top-7 right-7 w-[60px] h-[60px] text-black dark:text-gray-300"
         viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.75"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 2v4"/>
        <path d="M16 2v4"/>
        <rect width="18" height="18" x="3" y="4" rx="2"/>
        <path d="M3 10h18"/>
        <path d="M8 14h.01"/>
        <path d="M12 14h.01"/>
        <path d="M16 14h.01"/>
        <path d="M8 18h.01"/>
        <path d="M12 18h.01"/>
        <path d="M16 18h.01"/>
    </svg>
    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Isi Absensi</p>
    <a href="{{ route('absent_users') }}"
   wire:navigate
   class="absolute bottom-5 right-6 flex items-center gap-1
          text-xs sm:text-sm font-semibold
          text-blue-700 dark:text-gray-400
          hover:text-blue-500 dark:hover:text-gray-200
          cursor-pointer
          transition-all duration-200 group">

    <span>Lihat</span>

    <svg xmlns="http://www.w3.org/2000/svg"
         width="16" height="16" viewBox="0 0 24 24"
         fill="none" stroke="currentColor"
         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
         class="transition-transform duration-200 group-hover:translate-x-1">
        <path d="m9 18 6-6-6-6"/>
    </svg>
    <x-placeholder-pattern class="absolute inset-0 size-full" />

</a>

</div>

<!-- Card Jurnal -->
<div
    class="relative overflow-hidden rounded-xl border-l-8 border-blue-500 border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 p-4 space-y-2 sm:space-y-4
           shadow-md hover:shadow-xl transition-shadow duration-200
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Jurnal Harian</h1>
     <svg xmlns="http://www.w3.org/2000/svg"
         class="absolute top-7 right-7 w-[60px] h-[60px]
                text-black dark:text-gray-300
                opacity-80"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="1.75"
         stroke-linecap="round"
         stroke-linejoin="round">

        <path d="M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4"/>
        <path d="M2 6h4"/>
        <path d="M2 10h4"/>
        <path d="M2 14h4"/>
        <path d="M2 18h4"/>
        <path d="M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/>
    </svg>
    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Hari PKL</p>
    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Jurnal Diisi</p>
  <a href="{{ route('jurnal_users') }}"
   wire:navigate
   class="absolute bottom-5 right-6 flex items-center gap-1
          text-xs sm:text-sm font-semibold
          text-blue-700 dark:text-gray-400
          hover:text-blue-500 dark:hover:text-gray-200
          cursor-pointer
          transition-all duration-200 group">

    <span>Lihat</span>

    <svg xmlns="http://www.w3.org/2000/svg"
         width="16" height="16" viewBox="0 0 24 24"
         fill="none" stroke="currentColor"
         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
         class="transition-transform duration-200 group-hover:translate-x-1">
        <path d="m9 18 6-6-6-6"/>
    </svg>
    <x-placeholder-pattern class="absolute inset-0 size-full" />

</a>
</div>

<!-- Card Divisi -->
<div
    class="relative overflow-hidden rounded-xl border-l-8 border-blue-500 border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800 p-4 space-y-2 sm:space-y-4
           shadow-md hover:shadow-xl transition-shadow duration-200
           aspect-[16/9] sm:aspect-video">
    <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Divisi</h1>
    <svg xmlns="http://www.w3.org/2000/svg"
         class="absolute top-7 right-7 w-[60px] h-[60px]
                text-black dark:text-gray-300
                opacity-80"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="1.75"
         stroke-linecap="round"
         stroke-linejoin="round">

        <path d="M16 10h2"/>
        <path d="M16 14h2"/>
        <path d="M6.17 15a3 3 0 0 1 5.66 0"/>
        <circle cx="9" cy="11" r="2"/>
        <rect x="2" y="5" width="20" height="14" rx="2"/>
    </svg>
    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Dalam Divisi</p>
  <a href="{{ route('divisi_users') }}"
   wire:navigate
   class="absolute bottom-5 right-6 flex items-center gap-1
          text-xs sm:text-sm font-semibold
          text-blue-700 dark:text-gray-400
          hover:text-blue-500 dark:hover:text-gray-200
          cursor-pointer
          transition-all duration-200 group">

    <span>Lihat</span>

    <svg xmlns="http://www.w3.org/2000/svg"
         width="16" height="16" viewBox="0 0 24 24"
         fill="none" stroke="currentColor"
         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
         class="transition-transform duration-200 group-hover:translate-x-1">
        <path d="m9 18 6-6-6-6"/>
    </svg>
    <x-placeholder-pattern class="absolute inset-0 size-full" />

</a>
</div>
    </div>

    
    
</x-layouts.app>
