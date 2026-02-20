<flux:modal name="export-absensi" class="md:w-[520px]">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-gradient-to-br from-[#3526B3]/10 to-[#8615D9]/10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#3526B3] dark:text-[#8615D9]">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" x2="12" y1="15" y2="3"/>
                </svg>
            </div>
            <div>
                <flux:heading size="lg">Export Rekapan Absensi</flux:heading>
                <flux:subheading>Pilih format dan rentang waktu untuk export data</flux:subheading>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <p class="text-sm text-blue-700 dark:text-blue-300">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <form wire:submit="export" class="space-y-5">
            <!-- Format Selection -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">
                    Format Export
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <!-- CSV Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="exportFormat" value="csv" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700
                            peer-checked:border-[#3526B3] dark:peer-checked:border-[#8615D9]
                            peer-checked:bg-gradient-to-br peer-checked:from-[#3526B3]/5 peer-checked:to-[#8615D9]/5
                            hover:border-neutral-300 dark:hover:border-neutral-600
                            transition-all duration-200">
                            <div class="flex flex-col items-center gap-2">
                                <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">CSV</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Spreadsheet</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#3526B3] dark:bg-[#8615D9]
                            flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </label>

                    <!-- Excel Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="exportFormat" value="excel" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700
                            peer-checked:border-[#3526B3] dark:peer-checked:border-[#8615D9]
                            peer-checked:bg-gradient-to-br peer-checked:from-[#3526B3]/5 peer-checked:to-[#8615D9]/5
                            hover:border-neutral-300 dark:hover:border-neutral-600
                            transition-all duration-200">
                            <div class="flex flex-col items-center gap-2">
                                <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 dark:text-emerald-400">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="3" y1="9" x2="21" y2="9"/>
                                        <line x1="3" y1="15" x2="21" y2="15"/>
                                        <line x1="9" y1="3" x2="9" y2="21"/>
                                        <line x1="15" y1="3" x2="15" y2="21"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">Excel</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">XLSX Format</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#3526B3] dark:bg-[#8615D9]
                            flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </label>

                    <!-- PDF Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="exportFormat" value="pdf" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700
                            peer-checked:border-[#3526B3] dark:peer-checked:border-[#8615D9]
                            peer-checked:bg-gradient-to-br peer-checked:from-[#3526B3]/5 peer-checked:to-[#8615D9]/5
                            hover:border-neutral-300 dark:hover:border-neutral-600
                            transition-all duration-200">
                            <div class="flex flex-col items-center gap-2">
                                <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 dark:text-red-400">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-200">PDF</p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Dokumen</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#3526B3] dark:bg-[#8615D9]
                            flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </label>
                </div>
                @error('exportFormat')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">
                    Rentang Waktu
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="export-absen-start" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">
                            Tanggal Mulai
                        </label>
                        <div class="relative">
                            <input type="date" id="export-absen-start" wire:model="startDate"
                                class="w-full px-3 py-2.5 rounded-lg border border-neutral-200 dark:border-neutral-700
                                    bg-white dark:bg-neutral-800 text-sm text-neutral-800 dark:text-neutral-200
                                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent
                                    transition-all duration-200">
                        </div>
                        @error('startDate')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="export-absen-end" class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1.5">
                            Tanggal Selesai
                        </label>
                        <div class="relative">
                            <input type="date" id="export-absen-end" wire:model="endDate"
                                class="w-full px-3 py-2.5 rounded-lg border border-neutral-200 dark:border-neutral-700
                                    bg-white dark:bg-neutral-800 text-sm text-neutral-800 dark:text-neutral-200
                                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent
                                    transition-all duration-200">
                        </div>
                        @error('endDate')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('startDate', '{{ now()->startOfWeek()->format('Y-m-d') }}'); $set('endDate', '{{ now()->endOfWeek()->format('Y-m-d') }}')"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700
                        bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400
                        hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Minggu Ini
                </button>
                <button type="button" wire:click="$set('startDate', '{{ now()->startOfMonth()->format('Y-m-d') }}'); $set('endDate', '{{ now()->endOfMonth()->format('Y-m-d') }}')"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700
                        bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400
                        hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Bulan Ini
                </button>
                <button type="button" wire:click="$set('startDate', '{{ now()->subMonth()->startOfMonth()->format('Y-m-d') }}'); $set('endDate', '{{ now()->subMonth()->endOfMonth()->format('Y-m-d') }}')"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-200 dark:border-neutral-700
                        bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400
                        hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                    Bulan Lalu
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <flux:button type="button" variant="ghost"
                    wire:click="$dispatch('modal-close', { name: 'export-absensi' })">
                    Batal
                </flux:button>
                <flux:button type="submit"
                    class="bg-gradient-to-r from-[#3526B3] to-[#8615D9] text-white hover:opacity-90 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    Export {{ strtoupper($exportFormat) }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>
