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

        {{-- Tombol Input Absent hanya untuk Murid --}}
        @role('murid')
        <div class="flex items-center">
            <flux:modal.trigger name="input-permission">
                <flux:button class="bg-white hover:bg-neutral-50 border border-black text-black! mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-text w-5 h-5 mr-1">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M10 9H8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                    </svg>
                    Izin
                </flux:button>
            </flux:modal.trigger>

            <flux:modal.trigger name="input-absent-user">
                <flux:button class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus w-5 h-5" aria-hidden="true">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Absen
                </flux:button>
            </flux:modal.trigger>
        </div>
        @endrole
    </div>

    {{-- Modal Input Absent untuk Murid --}}
    @role('murid')
    {{-- Modal is global now --}}
    <livewire:absent-permission-input />
    @endrole

    {{-- ========== TAMPILAN UNTUK ADMIN: Filter & Statistik ========== --}}
    @if($isAdmin)
        <div class="mb-6 space-y-6">
            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Murid --}}
                <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-neutral-500">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Murid</p>
                        <h3 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100 mt-1">{{ $stats['total_students'] ?? 0 }}</h3>
                    </div>
                </div>

                {{-- Hadir --}}
                <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Sudah Absen (Hadir)</p>
                        <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['present'] ?? 0 }}</h3>
                    </div>
                </div>

                {{-- Izin / Sakit --}}
                <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-500">
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Izin / Sakit</p>
                        <h3 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['permission'] ?? 0 }}</h3>
                    </div>
                </div>

                {{-- Belum Absen / Alpha --}}
                <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" x2="9" y1="9" y2="15" />
                            <line x1="9" x2="15" y1="9" y2="15" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Belum Absen (Alfa)</p>
                        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $stats['alpha'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                    {{-- Input Tanggal --}}
                    <div class="w-full md:w-48">
                        <label for="date-filter" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Tanggal</label>
                        <input type="date" wire:model.live="date" id="date-filter"
                            class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                    </div>

                    {{-- Input Search --}}
                    <div class="w-full md:w-64">
                        <label for="search-filter" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Cari Murid</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" id="search-filter" placeholder="Nama atau Email..."
                                class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm pl-9 focus:ring-[#3526B3] focus:border-[#3526B3]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-2.5 text-neutral-400">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown Status --}}
                    <div class="w-full md:w-48">
                        <label for="status-filter" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Status</label>
                        <select wire:model.live="status" id="status-filter"
                            class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alfa">Belum Absen (Alfa)</option>
                        </select>
                    </div>

                    {{-- Dropdown Divisi --}}
                    <div class="w-full md:w-48">
                        <label for="divisi-filter" class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1">Divisi</label>
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
        
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($students as $student)
                @php
                    // Cari status absen pada tanggal yang dipilih
                    $todayAbsent = $student->absents->firstWhere('absent_date', $selectedDate);
                @endphp
                <div class="relative overflow-hidden rounded-xl
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
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $student->email }}</p>
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
                            @if($todayAbsent)
                                <div class="flex items-center gap-2 p-3 rounded-lg
                                    @if(strtolower($todayAbsent->status) == 'hadir') bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800
                                    @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit'])) bg-yellow-50 border border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
                                    @else bg-neutral-50 border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 
                                    @endif">
                                    
                                    {{-- Icon --}}
                                    @if(strtolower($todayAbsent->status) == 'hadir')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 dark:text-green-400">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                    @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit']))
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 dark:text-yellow-400">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" x2="12" y1="8" y2="12" />
                                            <line x1="12" x2="12.01" y1="16" y2="16" />
                                        </svg>
                                    @endif

                                    <div>
                                        <p class="font-bold uppercase 
                                            @if(strtolower($todayAbsent->status) == 'hadir') text-green-700 dark:text-green-300
                                            @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit'])) text-yellow-700 dark:text-yellow-300
                                            @endif">
                                            {{ $todayAbsent->status }}
                                        </p>
                                        @if(strtolower($todayAbsent->status) != 'hadir' && $todayAbsent->reason)
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 italic">"{{ Str::limit($todayAbsent->reason, 30) }}"</p>
                                        @endif
                                        @if(strtolower($todayAbsent->status) == 'hadir')
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $todayAbsent->verification_method === 'selfie' ? 'Via Selfie' : 'Via QR Code' }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-600 dark:text-red-400">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="15" x2="9" y1="9" y2="15" />
                                        <line x1="9" x2="15" y1="9" y2="15" />
                                    </svg>
                                    <div>
                                        <p class="font-bold uppercase text-red-700 dark:text-red-300">Belum Absen</p>
                                        <p class="text-xs text-red-600/70 dark:text-red-400/70">Tidak ada data masuk</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    {{-- Footer: Lihat Detail --}}
                    <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                        <flux:modal.trigger name="detail-absent-{{ $student->id }}">
                            <flux:button variant="ghost" class="w-full justify-center text-[#3526B3] dark:text-[#8615D9]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Lihat Detail
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>

            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-neutral-500 dark:text-neutral-400">Tidak ada murid yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        @foreach($students as $student)
            {{-- Modal Detail Absensi --}}
            <flux:modal name="detail-absent-{{ $student->id }}" class="md:w-[800px]">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4">Detail Absensi: {{ $student->name }}</flux:heading>

                    @if($student->absents->count() > 0)
                        <div class="overflow-x-auto max-h-[60vh]">
                            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                                <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-700 sticky top-0">
                                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20">
                                        <th class="px-3 py-2">Tanggal</th>
                                        <th class="px-3 py-2">Status</th>
                                        <th class="px-3 py-2">Metode</th>
                                        <th class="px-3 py-2">Foto</th>
                                        <th class="px-3 py-2">Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->absents as $absent)
                                        <tr class="even:bg-neutral-50 dark:even:bg-neutral-700 border-b dark:border-neutral-600">
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span @class([
                                                    'px-2 py-0.5 rounded text-xs font-medium',
                                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => strtolower($absent->status) == 'hadir',
                                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => strtolower($absent->status) == 'izin',
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => strtolower($absent->status) == 'sakit',
                                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array(strtolower($absent->status), ['hadir', 'izin', 'sakit'])
                                                ])>
                                                    {{ ucfirst($absent->status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2">
                                                @if($absent->verification_method === 'selfie')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                                                            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                                                            <circle cx="12" cy="13" r="3"/>
                                                        </svg>
                                                        Selfie
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                                                            <rect width="5" height="5" x="3" y="3" rx="1"/>
                                                            <rect width="5" height="5" x="16" y="3" rx="1"/>
                                                            <rect width="5" height="5" x="3" y="16" rx="1"/>
                                                        </svg>
                                                        QR Code
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                @if($absent->selfie_path)
                                                    <button type="button"
                                                        x-data
                                                        @click="$dispatch('show-selfie', { url: '{{ asset('storage/' . $absent->selfie_path) }}', date: '{{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}' }); $dispatch('modal-show', { name: 'selfie-modal' })"
                                                        class="inline-flex items-center text-[#3526B3] dark:text-[#8615D9] hover:underline cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                                                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                                            <circle cx="9" cy="9" r="2"/>
                                                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                                        </svg>
                                                        Lihat
                                                    </button>
                                                @else
                                                    <span class="text-neutral-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                @if($absent->latitude && $absent->longitude)
                                                    <a href="https://www.google.com/maps?q={{ $absent->latitude }},{{ $absent->longitude }}" target="_blank"
                                                        class="inline-flex items-center text-[#3526B3] dark:text-[#8615D9] hover:underline text-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1">
                                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                                            <circle cx="12" cy="10" r="3"/>
                                                        </svg>
                                                        Maps
                                                    </a>
                                                    <div class="text-[10px] text-neutral-400 mt-0.5">
                                                        {{ number_format($absent->latitude, 5) }}, {{ number_format($absent->longitude, 5) }}
                                                    </div>
                                                @else
                                                    <span class="text-neutral-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-neutral-500 dark:text-neutral-400 text-center py-8">
                            Belum ada data absensi untuk murid ini.
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
        {{-- ========== TAMPILAN UNTUK MURID: Status Card & Tabel Riwayat ========== --}}
        
        <div class="mb-6 space-y-6">
            {{-- Filter Tanggal --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-200">Absensi Saya</h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Lihat status dan riwayat absensi Anda.</p>
                </div>
                <div class="w-40 md:w-48">
                    <input type="date" wire:model.live="date" 
                        class="w-full rounded-lg border-neutral-200 dark:border-neutral-700 dark:bg-neutral-900 text-sm focus:ring-[#3526B3] focus:border-[#3526B3]">
                </div>
            </div>

            {{-- Card Status Hari Ini (Berdasarkan Filter Tanggal) --}}
            <div class="bg-white dark:bg-neutral-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 p-6 opacity-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                    </svg>
                </div>
                
                <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider mb-4">
                    Status Tanggal: <span class="text-neutral-900 dark:text-neutral-100 font-bold">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                </h3>

                @if($todayAbsent)
                    <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                        {{-- Status Badge --}}
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-full 
                                @if(strtolower($todayAbsent->status) == 'hadir') bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400
                                @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit'])) bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400 
                                @endif">
                                @if(strtolower($todayAbsent->status) == 'hadir')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit']))
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" x2="19.07" y1="4.93" y2="19.07"/></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold uppercase 
                                    @if(strtolower($todayAbsent->status) == 'hadir') text-green-600 dark:text-green-400
                                    @elseif(in_array(strtolower($todayAbsent->status), ['izin', 'sakit'])) text-yellow-600 dark:text-yellow-400
                                    @else text-neutral-600 dark:text-neutral-400 
                                    @endif">
                                    {{ $todayAbsent->status }}
                                </h4>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $todayAbsent->reason ? Str::limit($todayAbsent->reason, 50) : 'Tercatat dalam sistem' }}
                                </p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-auto p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900/50 border border-neutral-100 dark:border-neutral-700/50">
                            <div>
                                <p class="text-xs text-neutral-500 mb-1">Waktu Masuk</p>
                                <p class="font-medium text-neutral-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($todayAbsent->created_at)->format('H:i') }} WIB
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500 mb-1">Metode Verifikasi</p>
                                <p class="font-medium text-neutral-800 dark:text-neutral-200">
                                    @if($todayAbsent->verification_method == 'selfie')
                                        <span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> Selfie</span>
                                    @elseif($todayAbsent->verification_method == 'qr_code')
                                        <span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/></svg> QR Code</span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Belum Absen --}}
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold uppercase text-red-600 dark:text-red-400">
                                BELUM ABSEN (ALFA)
                            </h4>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                Tidak ada data absensi untuk tanggal ini.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ========== TAMPILAN UNTUK MURID: Tabel List Absensi ========== --}}
        <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-400">
                    <tr class="text-neutral-600 dark:text-neutral-400">
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">
                    @forelse($absentUsers as $absentUser)
                        @if($loop->index < 5) {{-- Batasi tampilan riwayat di tabel --}}
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($absentUser->absent_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span @class([
                                    'px-2.5 py-1 rounded-full text-xs font-semibold',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => strtolower($absentUser->status) == 'hadir',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => strtolower($absentUser->status) == 'izin',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => strtolower($absentUser->status) == 'sakit',
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array(strtolower($absentUser->status), ['hadir', 'izin', 'sakit'])
                                ])>
                                    {{ ucfirst($absentUser->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $absentUser->reason ?? '-' }}
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3"
                                class="px-6 py-12 text-center text-neutral-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-neutral-300 mb-2"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/></svg>
                                    <p>Belum ada data absensi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links untuk Murid --}}
        @if($absentUsers && $absentUsers->hasPages())
            <div class="mt-6">
                {{ $absentUsers->links() }}
            </div>
        @endif
    @endif

    {{-- Modal Popup untuk Foto Selfie (Flux Version) --}}
    <flux:modal name="selfie-modal" class="md:min-h-[200px] md:min-w-[400px]">
        <div x-data="{ 
                imageUrl: '', 
                imageDate: '' 
            }"
            x-on:show-selfie.window="imageUrl = $event.detail.url; imageDate = $event.detail.date"
            class="flex flex-col h-full">
            
            <div class="mb-4">
                <flux:heading size="lg">Foto Selfie</flux:heading>
                <flux:subheading x-text="imageDate"></flux:subheading>
            </div>
            
            <div class="flex-1 flex items-center justify-center p-2 bg-neutral-100 dark:bg-neutral-900 rounded-lg">
                <template x-if="imageUrl">
                    <img :src="imageUrl" alt="Foto Selfie Absensi" 
                        class="max-w-full max-h-[60vh] object-contain rounded-md shadow-sm">
                </template>
                <template x-if="!imageUrl">
                    <div class="text-neutral-400">Memuat gambar...</div>
                </template>
            </div>
            
            <div class="mt-6 flex justify-end">
                <flux:button @click="$dispatch('modal-close', { name: 'selfie-modal' })">Tutup</flux:button>
            </div>
        </div>
    </flux:modal>
</div>