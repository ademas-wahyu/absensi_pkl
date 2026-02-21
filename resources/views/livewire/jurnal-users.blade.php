<div>
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

        <div class="flex items-center gap-2">
            <flux:modal.trigger name="export-jurnal">
                <flux:button
                    class="bg-white hover:bg-neutral-50 border border-neutral-300 text-neutral-700! dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-300!">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-1">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Export Rekapan
                </flux:button>
            </flux:modal.trigger>

            @role('murid')
            <flux:modal.trigger name="input-jurnal-user">
                <flux:button class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus w-5 h-5" aria-hidden="true">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Input Jurnal
                </flux:button>
            </flux:modal.trigger>
            @endrole
        </div>
    </div>

    {{-- Modal Export Rekapan Jurnal --}}
    <livewire:export-jurnal />

    @role('murid')
    <livewire:jurnal-user-input />
    @endrole

    @if($isAdmin)
        {{-- ========== TAMPILAN UNTUK ADMIN: Filter & Statistik ========== --}}
        <div class="mb-6 space-y-6">
            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Murid --}}
                <div
                    class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="text-neutral-500">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Murid</p>
                        <h3 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100 mt-1">
                            {{ $stats['total_students'] ?? 0 }}
                        </h3>
                    </div>
                </div>

                {{-- Sudah Mengisi Jurnal --}}
                <div
                    class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="text-green-500">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Sudah Mengisi Jurnal</p>
                        <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $stats['sudah_mengisi'] ?? 0 }}
                        </h3>
                    </div>
                </div>

                {{-- Total Jurnal Hari Ini --}}
                <div
                    class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="text-blue-500">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" x2="8" y1="13" y2="13" />
                            <line x1="16" x2="8" y1="17" y2="17" />
                            <line x1="10" x2="8" y1="9" y2="9" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Jurnal Hari Ini</p>
                        <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ $stats['total_jurnal_today'] ?? 0 }}
                        </h3>
                    </div>
                </div>

                {{-- Belum Mengisi --}}
                <div
                    class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="text-red-500">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" x2="9" y1="9" y2="15" />
                            <line x1="9" x2="15" y1="9" y2="15" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Belum Mengisi Jurnal</p>
                        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                            {{ $stats['belum_mengisi'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div
                class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                    {{-- Input Tanggal --}}
                    <div class="w-full md:w-48">
                        <label for="date-filter"
                            class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Tanggal</label>
                        <input type="date" wire:model.live="date" id="date-filter"
                            class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                    </div>

                    {{-- Input Search --}}
                    <div class="w-full md:w-64">
                        <label for="search-filter"
                            class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Cari Murid</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" id="search-filter"
                                placeholder="Nama atau Email..."
                                class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm pl-9 focus:ring-[#3526B3] focus:border-[#3526B3]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="absolute left-3 top-2.5 text-neutral-400">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown Divisi --}}
                    <div class="w-full md:w-48">
                        <label for="divisi-filter"
                            class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Divisi</label>
                        <select wire:model.live="divisi" id="divisi-filter"
                            class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div }}">{{ $div }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="jurnal-cards-grid" class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
            @forelse($students as $student)
                @php
                    // Cari jurnal pada tanggal yang dipilih
                    $todayJurnal = $student->jurnals->firstWhere('jurnal_date', $selectedDate);
                @endphp
                <div class="relative w-full min-w-0 overflow-hidden rounded-xl
                                                                    border border-neutral-200 dark:border-neutral-700
                                                                    bg-white dark:bg-neutral-800
                                                                    shadow-md hover:shadow-xl transition-shadow duration-200">

                    {{-- Header Card --}}
                    <div class="p-4 border-b border-neutral-200 dark:border-neutral-700
                                                                bg-linear-to-r from-[#3526B3]/10 to-[#8615D9]/10">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-linear-to-br from-[#3526B3] to-[#8615D9] 
                                                                        flex items-center justify-center text-white font-semibold text-lg">
                                {{ strtoupper(substr($student->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $student->name }}</h3>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 break-all">{{ $student->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status Hari Ini (Berdasarkan Filter Tanggal) --}}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                                Status {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                            </span>
                        </div>

                        <div class="mb-4">
                            @if($todayJurnal)
                                <div
                                    class="flex items-center gap-2 p-3 rounded-lg border bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800">
                                    {{-- Icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" class="text-green-600 dark:text-green-400">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg>

                                    <div>
                                        <p class="font-bold uppercase text-green-700 dark:text-green-300">Sudah Mengisi</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 italic">
                                            "{{ Str::limit($todayJurnal->activity, 30) }}"</p>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" class="text-red-600 dark:text-red-400">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="15" x2="9" y1="9" y2="15" />
                                        <line x1="9" x2="15" y1="9" y2="15" />
                                    </svg>
                                    <div>
                                        <p class="font-bold uppercase text-red-700 dark:text-red-300">Belum Mengisi</p>
                                        <p class="text-xs text-red-600/70 dark:text-red-400/70">Tidak ada jurnal hari ini</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer: Lihat Detail --}}
                        <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                            <flux:modal.trigger name="detail-jurnal-{{ $student->id }}">
                                <flux:button variant="ghost" class="w-full justify-center text-[#3526B3] dark:text-[#8615D9]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Lihat Detail
                                </flux:button>
                            </flux:modal.trigger>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-neutral-500 dark:text-neutral-400">Tidak ada murid yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        @foreach($students as $student)
            {{-- Modal Detail Jurnal --}}
            <flux:modal name="detail-jurnal-{{ $student->id }}" class="md:w-[800px]">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4">Jurnal Harian: {{ $student->name }}</flux:heading>

                    @if($student->jurnals->count() > 0)
                        <div class="overflow-x-auto max-h-[60vh]">
                            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                                <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-700 sticky top-0">
                                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20">
                                        <th class="px-3 py-2">Tanggal</th>
                                        <th class="px-3 py-2">Aktivitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->jurnals as $jurnal)
                                        <tr class="even:bg-neutral-50 dark:even:bg-neutral-700 border-b dark:border-neutral-600">
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($jurnal->jurnal_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-3 py-2">{{ $jurnal->activity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-neutral-500 dark:text-neutral-400 text-center py-8">
                            Belum ada data jurnal untuk murid ini.
                        </p>
                    @endif
                </div>
            </flux:modal>
        @endforeach

        {{-- Pagination Links untuk Admin --}}
        @if($students && $students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif

    @else
        {{-- TAMPILAN MURID --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20 text-[#3526B3] dark:text-[#8615D9]">
                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Tanggal</th>
                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Aktivitas</th>
                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnalUsers as $jurnalUser)
                        <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                                {{ \Carbon\Carbon::parse($jurnalUser->jurnal_date)->format('d M Y') }}
                            </td>
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                                {{ $jurnalUser->activity }}
                                @if($jurnalUser->is_pending_edit)
                                    <br>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300 mt-1">
                                        Menunggu Persetujuan
                                    </span>
                                @endif
                            </td>
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700 text-center">
                                <div class="inline-flex items-center gap-3">
                                    <flux:button type="button" wire:click="edit({{ $jurnalUser->id }})" variant="ghost"
                                        size="sm" class="p-0! bg-transparent! border-0! shadow-none!" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="#002aff" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path
                                                d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                        </svg>
                                    </flux:button>

                                    <button type="button"
                                        onclick="confirm('Yakin ingin menghapus jurnal ini?') || event.stopImmediatePropagation()"
                                        wire:click="prepareDelete({{ $jurnalUser->id }})"
                                        class="text-red-600 hover:text-red-800" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="#ff0000" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-neutral-500">Belum ada data jurnal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links untuk Murid --}}
        @if($jurnalUsers && $jurnalUsers->hasPages())
            <div class="mt-6">
                {{ $jurnalUsers->links() }}
            </div>
        @endif
    @endif

    @role('murid')
    <livewire:jurnal-edit />
    @endrole
</div>