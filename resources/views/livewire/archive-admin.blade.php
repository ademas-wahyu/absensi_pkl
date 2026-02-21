<div>
    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header dengan Total --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-200">
                Arsip Non-Aktif
            </h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Daftar akun Anak PKL dan Mentor yang sudah tidak aktif
            </p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-neutral-200 dark:border-neutral-700">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            <button wire:click="setTab('murid')"
                class="{{ $activeTab === 'murid' ? 'border-[#3526B3] dark:border-[#8615D9] text-[#3526B3] dark:text-[#8615D9]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hover:border-neutral-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Anak PKL ({{ $totalStudents ?? 0 }})
            </button>
            <button wire:click="setTab('mentor')"
                class="{{ $activeTab === 'mentor' ? 'border-[#3526B3] dark:border-[#8615D9] text-[#3526B3] dark:text-[#8615D9]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hover:border-neutral-600' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Mentor ({{ $totalMentors ?? 0 }})
            </button>
        </nav>
    </div>

    {{-- Filter Section --}}
    <div
        class="mb-6 p-4 rounded-xl bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Cari
                    Nama/Email/Divisi/Sekolah</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik untuk mencari..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                    bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
            </div>

            {{-- Reset Button --}}
            <div class="flex items-end">
                <button wire:click="resetFilters" class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                    text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-700
                    transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="inline mr-1">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                        <path d="M21 3v5h-5" />
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                        <path d="M8 16H3v5" />
                    </svg>
                    Reset Pencarian
                </button>
            </div>
        </div>
    </div>

    {{-- Table Anak PKL --}}
    @if($activeTab === 'murid')
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300 relative">
                <div wire:loading.flex wire:target="search, gotoPage, previousPage, nextPage, setTab, activateUser"
                    class="absolute inset-0 bg-white/50 dark:bg-neutral-800/50 flex items-center justify-center z-10 backdrop-blur-sm rounded-xl">
                    <div class="flex items-center gap-2 text-[#3526B3] dark:text-[#8615D9]">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="font-medium text-sm">Memuat data...</span>
                    </div>
                </div>
                <thead class="text-xs uppercase">
                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20 text-[#3526B3] dark:text-[#8615D9]">
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Divisi</th>
                        <th class="px-4 py-3 font-semibold">Sekolah/Universitas</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($students as $index => $student)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                            <td class="px-4 py-3">{{ $students->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="font-medium text-neutral-500 line-through">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $student->email }}</td>
                            <td class="px-4 py-3">
                                @if($student->divisi)
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium 
                                                                                                                                                                        bg-neutral-200 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                        {{ $student->divisi }}
                                    </span>
                                @else
                                    <span class="text-neutral-400 dark:text-neutral-500 italic">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($student->sekolah)
                                    {{ $student->sekolah }}
                                @else
                                    <span class="text-neutral-400 dark:text-neutral-500 italic">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <flux:button variant="ghost" size="sm"
                                    class="text-green-600 hover:text-green-700 hover:bg-green-50 dark:text-green-500 dark:hover:bg-green-900/20"
                                    icon="arrow-path" title="Aktifkan Kembali" x-on:click.prevent="
                                                Swal.fire({
                                                    title: 'Aktifkan Kembali?',
                                                    text: 'Anak PKL ini akan memiliki akses kembali ke sistem.',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3526B3',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya, Aktifkan!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.activateUser({{ $student->id }});
                                                    }
                                                })
                                            ">
                                    Aktifkan
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                @if($search)
                                    Tidak ada anak PKL yang sesuai dengan pencarian.
                                @else
                                    Tidak ada anak PKL di dalam arsip.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- Table Mentor --}}
    @if($activeTab === 'mentor')
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
            <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300 relative">
                <div wire:loading.flex wire:target="search, gotoPage, previousPage, nextPage, setTab, activateMentor"
                    class="absolute inset-0 bg-white/50 dark:bg-neutral-800/50 flex items-center justify-center z-10 backdrop-blur-sm rounded-xl">
                    <div class="flex items-center gap-2 text-[#3526B3] dark:text-[#8615D9]">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="font-medium text-sm">Memuat data...</span>
                    </div>
                </div>
                <thead class="text-xs uppercase">
                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20 text-[#3526B3] dark:text-[#8615D9]">
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Nama Mentor</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Divisi</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($mentors as $index => $mentor)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                            <td class="px-4 py-3">{{ $mentors->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-neutral-500 line-through">{{ $mentor->nama_mentor }}</span>
                            </td>
                            <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $mentor->email ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium bg-neutral-200 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                    {{ $mentor->divisi->nama_divisi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <flux:button variant="ghost" size="sm"
                                    class="text-green-600 hover:text-green-700 hover:bg-green-50 dark:text-green-500 dark:hover:bg-green-900/20"
                                    icon="arrow-path" title="Aktifkan Kembali" x-on:click.prevent="
                                                Swal.fire({
                                                    title: 'Aktifkan Kembali?',
                                                    text: 'Mentor ini akan menjadi aktif kembali.',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3526B3',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya, Aktifkan!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.activateMentor({{ $mentor->id }});
                                                    }
                                                })
                                            ">
                                    Aktifkan
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-neutral-500 dark:text-neutral-400">
                                @if($search)
                                    Tidak ada mentor yang sesuai dengan pencarian.
                                @else
                                    Tidak ada mentor di dalam arsip.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pagination Anak PKL --}}
    @if($activeTab === 'murid' && $students->hasPages())
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif

    {{-- Pagination Mentor --}}
    @if($activeTab === 'mentor' && $mentors->hasPages())
        <div class="mt-4">
            {{ $mentors->links() }}
        </div>
    @endif
</div>