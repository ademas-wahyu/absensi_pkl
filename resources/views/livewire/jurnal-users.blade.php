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
        {{-- TAMPILAN ADMIN --}}
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($students as $student)
                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div
                        class="p-4 border-b border-neutral-200 dark:border-neutral-700 bg-linear-to-r from-[#3526B3]/10 to-[#8615D9]/10">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-linear-to-br from-[#3526B3] to-[#8615D9] flex items-center justify-center text-white font-semibold text-lg">
                                {{ strtoupper(substr($student->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-neutral-800 dark:text-neutral-200">{{ $student->name }}</h3>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $student->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">Total Jurnal</span>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold bg-[#3526B3]/10 text-[#3526B3] dark:bg-[#8615D9]/20 dark:text-[#8615D9]">
                                {{ $student->jurnals->count() }} entries
                            </span>
                        </div>

                        @if($student->jurnals->count() > 0)
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($student->jurnals->take(3) as $jurnal)
                                    <div class="p-2 rounded-lg bg-neutral-50 dark:bg-neutral-700/50">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ \Carbon\Carbon::parse($jurnal->jurnal_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-neutral-700 dark:text-neutral-300 line-clamp-2">
                                            {{ Str::limit($jurnal->activity, 80) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 text-center py-4">Belum ada data jurnal</p>
                        @endif
                    </div>

                    <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                        <flux:modal.trigger name="detail-jurnal-{{ $student->id }}">
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

                {{-- Modal Detail untuk Admin --}}
                <flux:modal name="detail-jurnal-{{ $student->id }}" class="md:w-[700px]">
                    <div class="p-6">
                        <flux:heading size="lg" class="mb-4">Jurnal Harian: {{ $student->name }}</flux:heading>
                        @if($student->jurnals->count() > 0)
                            <div class="overflow-x-auto max-h-[60vh]">
                                <table class="w-full text-sm text-left">
                                    <thead class="sticky top-0 bg-neutral-50 dark:bg-neutral-700">
                                        <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20">
                                            <th class="px-4 py-2">Tanggal</th>
                                            <th class="px-4 py-2">Aktivitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->jurnals as $jurnal)
                                            <tr class="border-b dark:border-neutral-700">
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($jurnal->jurnal_date)->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-2">{{ $jurnal->activity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center py-8">Tidak ada data.</p>
                        @endif
                    </div>
                </flux:modal>
            @empty
                <div class="col-span-full text-center py-12">Tidak ada murid.</div>
            @endforelse
        </div>

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
                            <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $jurnalUser->activity }}
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