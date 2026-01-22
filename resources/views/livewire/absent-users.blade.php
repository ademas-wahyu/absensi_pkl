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
            <flux:modal.trigger name="input-absent-user">
                <flux:button class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus w-5 h-5" aria-hidden="true">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Input Absent
                </flux:button>
            </flux:modal.trigger>
        </div>
        @endrole
    </div>

    {{-- Modal Input Absent untuk Murid --}}
    @role('murid')
    <livewire:absent-user-input />
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
                            <div class="w-12 h-12 rounded-full bg-linear-to-br from-[#3526B3] to-[#8615D9] 
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
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
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
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $absent->status == 'hadir',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => $absent->status == 'izin',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $absent->status == 'sakit',
                                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array($absent->status, ['hadir', 'izin', 'sakit'])
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
                <flux:modal name="detail-absent-{{ $student->id }}" class="md:w-[600px]">
                    <div class="p-6">
                        <flux:heading size="lg" class="mb-4">Detail Absensi: {{ $student->name }}</flux:heading>

                        @if($student->absents->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                                    <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-700">
                                        <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20">
                                            <th class="px-4 py-2">Tanggal</th>
                                            <th class="px-4 py-2">Status</th>
                                            <th class="px-4 py-2">Alasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->absents as $absent)
                                            <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span @class([
                                                        'px-2 py-0.5 rounded text-xs font-medium',
                                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $absent->status == 'hadir',
                                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => $absent->status == 'izin',
                                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $absent->status == 'sakit',
                                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array($absent->status, ['hadir', 'izin', 'sakit'])
                                                    ])>
                                                        {{ ucfirst($absent->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">{{ $absent->reason ?? '-' }}</td>
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
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $absentUser->status == 'hadir',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => $absentUser->status == 'izin',
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $absentUser->status == 'sakit',
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => !in_array($absentUser->status, ['hadir', 'izin', 'sakit'])
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
    @endif
</div>