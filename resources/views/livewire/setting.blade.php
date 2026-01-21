<div class="max-w-xl mx-auto space-y-6">
     <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-neutral-700 hover:text-neutral-900 dark:text-neutral-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-5 h-5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali</span>
            </a>
        </div>
</div>

    <!-- DIVISI -->
    <div
        class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-semibold mb-4">
            Pengaturan Divisi
        </h3>

        <div class="space-y-4">
            <flux:input
                wire:model="division"
                :label="__('Divisi')"
                type="text"
                placeholder="Contoh: IT Support"
                required
            />

            <flux:button wire:click="saveDivision" variant="primary" class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                Simpan Divisi
            </flux:button>

            @if (session()->has('division_saved'))
                <p class="text-sm text-green-600">
                    {{ session('division_saved') }}
                </p>
            @endif
        </div>
    </div>

        <!-- ASAL SEKOLAH -->
    <div
        class="bg-white dark:bg-neutral-800 shadow rounded-lg p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-semibold mb-4">
            Pengaturan Asal Sekolah
        </h3>

        <div class="space-y-4">
            <flux:input
                wire:model="school"
                :label="__('Asal Sekolah')"
                type="text"
                placeholder="Contoh: SMK Negeri 1"
                required
            />

            <flux:button wire:click="saveSchool" variant="primary" class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                Simpan Asal Sekolah
            </flux:button>

            @if (session()->has('school_saved'))
                <p class="text-sm text-green-600">
                    {{ session('school_saved') }}
                </p>
            @endif
        </div>
    </div>

</div>
