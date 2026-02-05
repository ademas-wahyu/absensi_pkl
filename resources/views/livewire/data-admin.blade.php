<div>
    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header dengan Total dan Tombol Tambah --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-200">
                Total Anak PKL: <span class="text-[#3526B3] dark:text-[#8615D9]">{{ $students->total() }}</span>
            </h2>
        </div>
        <flux:modal.trigger name="tambah-anak">
            <flux:button class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <line x1="19" x2="19" y1="8" y2="14" />
                        <line x1="22" x2="16" y1="11" y2="11" />
                    </svg>
                    <span>Tambah Anak PKL</span>
                </span>
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Modal Tambah/Edit Anak PKL --}}
    <flux:modal name="tambah-anak" class="md:w-[500px]">
        <div class="p-6">
            <flux:heading size="lg" class="mb-6">
                {{ $selectedUserId ? 'Edit Data Anak PKL' : 'Tambah Anak PKL Baru' }}
            </flux:heading>

            <form wire:submit="save" class="space-y-4">
                {{-- ... (inputs remain same) ... --}}

                {{-- (Skipped inputs for brevity in replacing chunks, assumed context allows targeting submit button
                directly if needed, but here I'm replacing the wrapper to update title) --}}

                {{-- Let's target the form tag and title specifically --}}

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama
                        Lengkap</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent"
                        placeholder="Masukkan nama lengkap">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent"
                        placeholder="contoh@email.com">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label
                        class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Password</label>
                    <input type="password" wire:model="password" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent"
                        placeholder="Minimal 6 karakter">
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Divisi --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Divisi</label>
                    <select wire:model="divisi" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisiOptions as $divisi)
                            <option value="{{ $divisi->nama_divisi }}">{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                    @error('divisi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Sekolah/Universitas --}}
                <div>
                    <label
                        class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Sekolah/Universitas</label>
                    <select wire:model="sekolah" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                        <option value="">Pilih Sekolah</option>
                        @foreach($sekolahList as $sekolah)
                            <option value="{{ $sekolah }}">{{ $sekolah }}</option>
                        @endforeach
                    </select>
                    @error('sekolah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Mentor --}}
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Mentor</label>
                    <select wire:model="mentor_id" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                        bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                        focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                        <option value="">Pilih Mentor (Opsional)</option>
                        @foreach($mentorList as $mentor)
                            <option value="{{ $mentor->id }}">{{ $mentor->nama_mentor }}</option>
                        @endforeach
                    </select>
                    @error('mentor_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost" wire:click="resetForm">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white!">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17,21 17,13 7,13 7,21" />
                                <polyline points="7,3 7,8 15,8" />
                            </svg>
                            <span>Simpan</span>
                        </span>
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Filter Section --}}
    <div
        class="mb-6 p-4 rounded-xl bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Cari
                    Nama/Email</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik untuk mencari..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                    bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
            </div>

            {{-- Filter Divisi --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Filter
                    Divisi</label>
                <select wire:model.live="filterDivisi" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                    bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                    <option value="">Semua Divisi</option>
                    @foreach($divisiList as $divisi)
                        <option value="{{ $divisi }}">{{ $divisi }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Sekolah --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Filter
                    Sekolah/Universitas</label>
                <select wire:model.live="filterSekolah" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                    bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
                    <option value="">Semua Sekolah/Universitas</option>
                    @foreach($sekolahList as $sekolah)
                        <option value="{{ $sekolah }}">{{ $sekolah }}</option>
                    @endforeach
                </select>
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
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div
        class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
        <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
                <thead class="text-xs uppercase">
                    <tr class="bg-[#3526B3]/10 dark:bg-[#8615D9]/20 text-[#3526B3] dark:text-[#8615D9]">
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Divisi</th>
                        <th class="px-4 py-3 font-semibold">Sekolah/Universitas</th>
                        <th class="px-4 py-3 font-semibold">Mentor</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($students as $index => $student)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <td class="px-4 py-3">{{ $students->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-linear-to-br from-[#3526B3] to-[#8615D9] 
                                                                            flex items-center justify-center text-white font-semibold text-xs">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <span class="font-medium">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $student->email }}</td>
                        <td class="px-4 py-3">
                            @if($student->divisi)
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium 
                                                                                                                    bg-[#3526B3]/10 text-[#3526B3] dark:bg-[#8615D9]/20 dark:text-[#8615D9]">
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
                            @if($student->mentor)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $student->mentor->nama_mentor }}
                                </span>
                            @else
                                <span class="text-neutral-400 dark:text-neutral-500 italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <flux:button variant="ghost" size="sm" wire:click="edit({{ $student->id }})"
                                icon="pencil-square">
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-neutral-500 dark:text-neutral-400">
                            @if($search || $filterDivisi || $filterSekolah)
                                Tidak ada data yang sesuai dengan filter.
                            @else
                                Belum ada anak PKL yang terdaftar.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif
</div>