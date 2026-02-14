<flux:modal name="export-absensi" class="md:w-[480px]">
    <div class="p-6">
        <flux:heading size="lg" class="mb-1">Export Rekapan Absensi</flux:heading>
        <flux:subheading class="mb-6">Pilih rentang waktu untuk export data absensi ke CSV.</flux:subheading>

        <form wire:submit="export" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="export-absen-start"
                        class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="export-absen-start" wire:model="startDate"
                        class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                    @error('startDate')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="export-absen-end"
                        class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        Tanggal Selesai
                    </label>
                    <input type="date" id="export-absen-end" wire:model="endDate"
                        class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                    @error('endDate')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost"
                    @click="$dispatch('modal-close', { name: 'export-absensi' })">
                    Batal
                </flux:button>
                <flux:button type="submit"
                    class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-1">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Export CSV
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>