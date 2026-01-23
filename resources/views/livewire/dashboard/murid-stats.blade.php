<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component {
    public int $totalJurnalFilled;
    public int $totalWorkDays;
    public string $userDivisi;

    public function mount()
    {
        $user = Auth::user();

        // Hitung jumlah jurnal yang sudah diisi
        $this->totalJurnalFilled = DB::table("jurnal_users")
            ->where("user_id", $user->id)
            ->count();

        // Hitung total hari kerja (dari jurnal pertama sampai sekarang)
        $firstJurnal = DB::table("jurnal_users")
            ->where("user_id", $user->id)
            ->orderBy("jurnal_date", "asc")
            ->first();

        if ($firstJurnal) {
            $startDate = Carbon::parse($firstJurnal->jurnal_date);
            $today = Carbon::today();

            // Hitung hari kerja (Senin-Jumat saja)
            $this->totalWorkDays =
                $startDate->diffInDaysFiltered(function (Carbon $date) {
                    return $date->isWeekday();
                }, $today) + 1;
        } else {
            $this->totalWorkDays = 0;
        }

        // Ambil divisi user
        $this->userDivisi = $user->divisi ?? "Belum ada divisi";
    }
};
?>

<div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
    <!-- Card Absensi -->
    <div class="relative overflow-hidden rounded-xl
        border border-neutral-200 dark:border-neutral-700
        before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
        before:bg-linear-to-b before:from-[#3526B3] before:to-[#8615D9]
        bg-white dark:bg-neutral-800
        p-4 pl-5 space-y-2 sm:space-y-4
        shadow-md hover:shadow-xl transition-shadow duration-200
        aspect-video sm:aspect-video">
        <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Absensi</h1>
        <svg xmlns="http://www.w3.org/2000/svg"
            class="absolute top-7 right-7 w-[60px] h-[60px] text-black dark:text-gray-300" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M8 2v4" />
            <path d="M16 2v4" />
            <rect width="18" height="18" x="3" y="4" rx="2" />
            <path d="M3 10h18" />
            <path d="M8 14h.01" />
            <path d="M12 14h.01" />
            <path d="M16 14h.01" />
            <path d="M8 18h.01" />
            <path d="M12 18h.01" />
            <path d="M16 18h.01" />
        </svg>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Isi Absensi</p>
        <a href="{{ route('absent_users') }}" wire:navigate class="absolute bottom-5 right-6 flex items-center gap-1
            text-xs sm:text-sm font-semibold
            text-zinc-700 dark:text-zinc-200
            hover:text-[#3526B3] dark:hover:text-[#8615D9]
            data-current:bg-linear-to-r
            data-current:from-[#3526B3]
            data-current:to-[#8615D9]
            data-current:text-white
            data-current:shadow-md
            cursor-pointer
            transition-all duration-200 group">
            <span>Lihat</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                class="transition-transform duration-200 group-hover:translate-x-1">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <x-placeholder-pattern class="absolute inset-0 size-full" />
        </a>
    </div>

    <!-- Card Jurnal -->
    <div class="relative overflow-hidden rounded-xl
        border border-neutral-200 dark:border-neutral-700
        before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
        before:bg-linear-to-b before:from-[#3526B3] before:to-[#8615D9]
        bg-white dark:bg-neutral-800
        p-4 pl-5 space-y-2 sm:space-y-4
        shadow-md hover:shadow-xl transition-shadow duration-200
        aspect-video sm:aspect-video">
        <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Jurnal Harian</h1>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-7 right-7 w-[60px] h-[60px]
            text-black dark:text-gray-300
            opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4" />
            <path d="M2 6h4" />
            <path d="M2 10h4" />
            <path d="M2 14h4" />
            <path d="M2 18h4" />
            <path
                d="M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
        </svg>
        <div class="flex items-center gap-2 mt-2">
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Hari PKL:</p>
            <span class="text-xs sm:text-sm font-bold text-[#3526B3] dark:text-[#8615D9]">{{ $totalWorkDays }} hari</span>
        </div>
        <div class="flex items-center gap-2">
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Jurnal Diisi:</p>
            <span class="text-xs sm:text-sm font-bold text-[#3526B3] dark:text-[#8615D9]">{{ $totalJurnalFilled }} jurnal</span>
        </div>
        <a href="{{ route('jurnal_users') }}" wire:navigate class="absolute bottom-5 right-6 flex items-center gap-1
            text-xs sm:text-sm font-semibold
            text-zinc-700 dark:text-zinc-200
            hover:text-[#3526B3] dark:hover:text-[#8615D9]
            data-current:bg-linear-to-r
            data-current:from-[#3526B3]
            data-current:to-[#8615D9]
            data-current:text-white
            data-current:shadow-md
            cursor-pointer
            transition-all duration-200 group">
            <span>Lihat</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                class="transition-transform duration-200 group-hover:translate-x-1">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <x-placeholder-pattern class="absolute inset-0 size-full" />
        </a>
    </div>

    <!-- Card Divisi -->
    <div class="relative overflow-hidden rounded-xl
        border border-neutral-200 dark:border-neutral-700
        before:absolute before:left-0 before:top-0 before:h-full before:w-[6px]
        before:bg-linear-to-b before:from-[#3526B3] before:to-[#8615D9]
        bg-white dark:bg-neutral-800
        p-4 pl-5 space-y-2 sm:space-y-4
        shadow-md hover:shadow-xl transition-shadow duration-200
        aspect-video sm:aspect-video">
        <h1 class="text-base sm:text-lg font-semibold dark:text-gray-400 mt-2">Divisi</h1>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-7 right-7 w-[60px] h-[60px]
            text-black dark:text-gray-300
            opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 10h2" />
            <path d="M16 14h2" />
            <path d="M6.17 15a3 3 0 0 1 5.66 0" />
            <circle cx="9" cy="11" r="2" />
            <rect x="2" y="5" width="20" height="14" rx="2" />
        </svg>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Dalam Divisi:</p>
        <p class="text-sm sm:text-base font-bold text-[#3526B3] dark:text-[#8615D9]">{{ $userDivisi }}</p>
        <a href="{{ route('divisi_users') }}" wire:navigate class="absolute bottom-5 right-6 flex items-center gap-1
            text-xs sm:text-sm font-semibold
            text-zinc-700 dark:text-zinc-200
            hover:text-[#3526B3] dark:hover:text-[#8615D9]
            data-current:bg-linear-to-r
            data-current:from-[#3526B3]
            data-current:to-[#8615D9]
            data-current:text-white
            data-current:shadow-md
            cursor-pointer
            transition-all duration-200 group">
            <span>Lihat</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                class="transition-transform duration-200 group-hover:translate-x-1">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <x-placeholder-pattern class="absolute inset-0 size-full" />
        </a>
    </div>
</div>
