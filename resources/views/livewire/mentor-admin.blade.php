<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-200">
                Daftar Mentor
            </h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Kelola data mentor pembimbing</p>
        </div>
        <flux:button wire:click="openFormModal"
            class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="19" x2="19" y1="8" y2="14" />
                    <line x1="22" x2="16" y1="11" y2="11" />
                </svg>
                <span>Tambah Mentor</span>
            </span>
        </flux:button>
    </div>

    {{-- Search --}}
    <div
        class="mb-6 p-4 rounded-xl bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Cari
                    Nama/Email</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik untuk mencari..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600 
                    bg-white dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200
                    focus:ring-2 focus:ring-[#3526B3] dark:focus:ring-[#8615D9] focus:border-transparent">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div
        class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm">
        <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300 relative">
            <div wire:loading.flex wire:target="search, gotoPage, previousPage, nextPage, toggleActive, deleteMentor"
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
                    <th class="px-4 py-3 font-semibold">No. Telepon</th>
                    <th class="px-4 py-3 font-semibold">Divisi</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($mentors as $index => $mentor)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <td class="px-4 py-3">{{ $mentors->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium {{ !$mentor->is_active ? 'text-neutral-400 line-through' : '' }}">
                            {{ $mentor->nama_mentor }}
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $mentor->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $mentor->no_telepon ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                {{ $mentor->divisi->nama_divisi ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($mentor->is_active)
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" wire:click="editMentor({{ $mentor->id }})"
                                    icon="pencil-square" title="Edit Data">
                                </flux:button>
                                @if($mentor->is_active)
                                    <flux:button variant="ghost" size="sm"
                                        class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        icon="archive-box-x-mark" title="Non-Aktifkan" x-on:click.prevent="
                                                        Swal.fire({
                                                            title: 'Nonaktifkan Mentor?',
                                                            text: 'Mentor ini tidak akan muncul lagi di daftar aktif.',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3526B3',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Ya, Nonaktifkan!',
                                                            cancelButtonText: 'Batal'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                $wire.toggleActive({{ $mentor->id }});
                                                            }
                                                        })
                                                    ">
                                    </flux:button>
                                @else
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
                                                                $wire.toggleActive({{ $mentor->id }});
                                                            }
                                                        })
                                                    ">
                                    </flux:button>
                                @endif
                                <flux:button variant="ghost" size="sm"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-100 dark:hover:bg-red-900/30"
                                    icon="trash" title="Hapus Permanen" x-on:click.prevent="
                                                Swal.fire({
                                                    title: 'Hapus Mentor?',
                                                    text: 'Data mentor ini akan dihapus secara permanen!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'Ya, Hapus!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.deleteMentor({{ $mentor->id }});
                                                    }
                                                })
                                            ">
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-neutral-500 dark:text-neutral-400">
                            @if($search)
                                Tidak ada mentor yang sesuai dengan pencarian.
                            @else
                                Belum ada data mentor.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($mentors->hasPages())
        <div class="mt-4">
            {{ $mentors->links() }}
        </div>
    @endif

    {{-- Modal Form Tambah/Edit --}}
    @if($showFormModal)
        <flux:modal wire:model="showFormModal" class="md:w-[500px] space-y-6">
            <div>
                <flux:heading size="lg">{{ $editMentorId ? 'Edit Mentor' : 'Tambah Mentor' }}</flux:heading>
                <flux:subheading>{{ $editMentorId ? 'Perbarui data mentor' : 'Masukkan data mentor baru' }}
                </flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="nama_mentor" label="Nama Mentor" placeholder="Budi Santoso" />
                @error('nama_mentor')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <flux:input wire:model="email_mentor" label="Email" placeholder="mentor@example.com" type="email" />

                <flux:input wire:model="no_telepon_mentor" label="No. Telepon" placeholder="08123456789" />

                <flux:select wire:model="divisi_id_mentor" label="Divisi" placeholder="Pilih Divisi">
                    @foreach($divisiOptions as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </flux:select>
                @error('divisi_id_mentor')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror

                <flux:textarea wire:model="keahlian_mentor" label="Keahlian" placeholder="Frontend Development, UI/UX"
                    rows="3" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="closeFormModal" variant="ghost">Batal</flux:button>
                <flux:button wire:click="saveMentor"
                    class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    {{ $editMentorId ? 'Update' : 'Simpan' }}
                </flux:button>
            </div>
        </flux:modal>
    @endif
</div>