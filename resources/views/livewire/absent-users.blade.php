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

    {{-- ========== TAMPILAN UNTUK ADMIN: Card per Murid ========== --}}
    @if($isAdmin)
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($students as $student)
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

                    {{-- Statistik Absensi --}}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Total Absensi</span>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                                                                                bg-[#3526B3]/10 text-[#3526B3] dark:bg-[#8615D9]/20 dark:text-[#8615D9]">
                                {{ $student->absents->count() }} records
                            </span>
                        </div>

                        {{-- Recent Absents --}}
                        @if($student->absents->count() > 0)
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($student->absents->take(3) as $absent)
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-neutral-50 dark:bg-neutral-700/50">
                                        <span class="text-xs text-neutral-600 dark:text-neutral-400">
                                            {{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}
                                        </span>
                                        <span @class([
                                            'px-2 py-0.5 rounded text-xs font-medium',
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => strtolower($absent->status) == 'hadir',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => strtolower($absent->status) == 'izin',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => strtolower($absent->status) == 'sakit',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array(strtolower($absent->status), ['hadir', 'izin', 'sakit'])
                                        ])>
                                            {{ ucfirst($absent->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 text-center py-4">
                                Belum ada data absensi
                            </p>
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
                                                            @click="$dispatch('show-selfie', { url: '{{ asset('storage/' . $absent->selfie_path) }}', date: '{{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}' })"
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
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-neutral-500 dark:text-neutral-400">Tidak ada murid yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Links untuk Admin --}}
        @if($students && $students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif

    @else
        {{-- ========== TAMPILAN UNTUK MURID: Tabel List Absensi ========== --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:text-neutral-400">
                    <tr class="
                                                bg-[#3526B3]/10
                                                dark:bg-[#8615D9]/20
                                                text-[#3526B3]
                                                dark:text-[#8615D9]
                                                text-left
                                    ">

                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Tanggal</th>
                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Status</th>
                        <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absentUsers as $absentUser)
                        <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                                {{ \Carbon\Carbon::parse($absentUser->absent_date)->format('d M Y') }}
                            </td>
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                                <span @class([
                                    'px-2 py-0.5 rounded text-xs font-medium',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => strtolower($absentUser->status) == 'hadir',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => strtolower($absentUser->status) == 'izin',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => strtolower($absentUser->status) == 'sakit',
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array(strtolower($absentUser->status), ['hadir', 'izin', 'sakit'])
                                ])>
                                    {{ ucfirst($absentUser->status) }}
                                </span>
                            </td>
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                                {{ $absentUser->reason ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3"
                                class="border border-neutral-300 px-4 py-8 dark:border-neutral-700 text-center text-neutral-500">
                                Belum ada data absensi. Silakan input absensi pertama Anda.
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

    {{-- Modal Popup untuk Foto Selfie --}}
    <div x-data="{ 
            showModal: false, 
            imageUrl: '', 
            imageDate: '' 
        }"
        x-on:show-selfie.window="showModal = true; imageUrl = $event.detail.url; imageDate = $event.detail.date"
        x-show="showModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        style="display: none;">
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70" @click="showModal = false"></div>
        
        {{-- Modal Content --}}
        <div class="relative bg-white dark:bg-neutral-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700">
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">Foto Selfie</h3>
                    <p class="text-sm text-neutral-500" x-text="imageDate"></p>
                </div>
                <button @click="showModal = false" 
                    class="p-2 rounded-full hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- Image --}}
            <div class="p-4">
                <img :src="imageUrl" alt="Foto Selfie Absensi" 
                    class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
            </div>
        </div>
    </div>
</div>