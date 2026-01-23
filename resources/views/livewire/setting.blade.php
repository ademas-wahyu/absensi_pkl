<div class="max-w-xl md:max-w-6xl mx-auto space-y-3 px-4">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-neutral-700 hover:text-neutral-900 dark:text-neutral-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-left-icon lucide-chevron-left w-5 h-5" aria-hidden="true">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- CARD SETTINGS -->
    <div class="flex flex-col gap-4 md:flex-row md:gap-6">
        <!-- CARD 1 : DIVISI -->
        <div class="flex flex-col gap-4
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">



            <!-- Header -->
            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Pengaturan Divisi
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Tambahkan atau kelola divisi
                </p>
            </div>

            <!-- Form -->
            <div class="space-y-5 pl-4">
                <flux:input wire:model.defer="division" :label="__('Nama Divisi')" placeholder="IT Support" />

                <flux:button wire:click="saveDivision" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9]
                       text-white! hover:opacity-90">
                    Simpan Perubahan
                </flux:button>

                <div class="pt-1 text-right">
                    <a href="{{ route('divisi_users') }}" class="text-sm text-neutral-500 dark:text-neutral-400
                          hover:text-[#3526B3] hover:underline transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD 2 : ASAL SEKOLAH -->
        <div class="flex flex-col gap-4
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">



            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Asal Sekolah
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Kelola asal sekolah
                </p>
            </div>

            <div class="space-y-5 pl-4">
                <flux:input wire:model.defer="school" :label="__('Nama Sekolah')" placeholder="SMK Negeri 1" />

                <flux:button wire:click="saveDivision" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9]
                       text-white! hover:opacity-90">
                    Simpan Perubahan
                </flux:button>

                <div class="pt-1 text-right">
                    <a href="{{ route('divisi_users') }}" class="text-sm text-neutral-500 dark:text-neutral-400
                          hover:text-[#3526B3] hover:underline transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD 3 : PERIODE PKL -->
        <div class="flex flex-col gap-4
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">



            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Nama Mentor
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Atur nama mentor
                </p>
            </div>

            <div class="space-y-5 pl-4">
                <flux:input wire:model.defer="mentor" :label="__('Nama Mentor')" placeholder="Budi Santoso" />

                <flux:button wire:click="saveDivision" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9]
                       text-white! hover:opacity-90">
                    Simpan Perubahan
                </flux:button>

                <div class="pt-1 text-right">
                    <a href="{{ route('divisi_users') }}" class="text-sm text-neutral-500 dark:text-neutral-400
                          hover:text-[#3526B3] hover:underline transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>