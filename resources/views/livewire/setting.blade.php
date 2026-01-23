<div class="max-w-xl md:max-w-6xl mx-auto space-y-3 px-4">
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
    </div>

    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300" role="alert">
            <svg class="inline w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="font-medium">Berhasil!</span> {{ session('message') }}
        </div>
    @endif

    <!-- CARD SETTINGS -->
    <div class="flex flex-col gap-4 md:flex-row md:gap-6">
        <!-- CARD 1 : DIVISI -->
        <div class="flex flex-col
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">

            <!-- Header -->
            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Pengaturan Divisi
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Tambahkan atau kelola divisi
                </p>
            </div>

            <!-- Form -->
            <div class="flex-1 space-y-5 pl-4">
                <flux:input wire:model="nama_divisi" label="Nama Divisi" placeholder="IT Support" />

                <flux:textarea wire:model="deskripsi_divisi" label="Deskripsi" placeholder="Deskripsi divisi"
                    rows="3" />
            </div>

            <!-- Buttons - Always at bottom -->
            <div class="space-y-3 pl-4 mt-auto pt-5">
                    <flux:button wire:click="saveDivisi" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                        Simpan Perubahan
                    </flux:button>

                    <div class="text-right">
                        <button wire:click="openDivisiModal" type="button"
                            class="text-sm text-neutral-500 dark:text-neutral-400
                              hover:text-[#3526B3] hover:underline transition">
                            Lihat Detail →
                        </button>
                    </div>
            </div>
        </div>

        <!-- CARD 2 : ASAL SEKOLAH -->
        <div class="flex flex-col
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">

            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Asal Sekolah
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Kelola asal sekolah
                </p>
            </div>

            <div class="flex-1 space-y-5 pl-4">
                <flux:input wire:model="nama_sekolah" label="Nama Sekolah" placeholder="SMK Negeri 1" />

                <flux:input wire:model="alamat_sekolah" label="Alamat" placeholder="Jl. Raya No. 123" />

                <flux:input wire:model="no_telepon_sekolah" label="No. Telepon" placeholder="021-1234567" />
            </div>

            <!-- Buttons - Always at bottom -->
            <div class="space-y-3 pl-4 mt-auto pt-5">
                    <flux:button wire:click="saveSekolah" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                        Simpan Perubahan
                    </flux:button>

                    <div class="text-right">
                        <button wire:click="openSekolahModal" type="button"
                            class="text-sm text-neutral-500 dark:text-neutral-400
                              hover:text-[#3526B3] hover:underline transition">
                            Lihat Detail →
                        </button>
                    </div>
            </div>
        </div>

        <!-- CARD 3 : MENTOR -->
        <div class="flex flex-col
           w-full md:flex-1
           rounded-2xl
           border border-neutral-200 dark:border-neutral-700
           bg-white dark:bg-neutral-800
           px-6 py-6
           shadow-md hover:shadow-lg
           transition">

            <div class="mb-5 pl-4">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                    Nama Mentor
                </h3>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Atur nama mentor
                </p>
            </div>

            <div class="flex-1 space-y-5 pl-4">
                <flux:input wire:model="nama_mentor" label="Nama Mentor" placeholder="Budi Santoso" />

                <flux:input wire:model="email_mentor" label="Email" placeholder="mentor@example.com" type="email" />

                <flux:input wire:model="no_telepon_mentor" label="No. Telepon" placeholder="08123456789" />

                <flux:select wire:model="divisi_id_mentor" label="Divisi" placeholder="Pilih Divisi">
                    @foreach ($divisiOptions as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </flux:select>

                <flux:textarea wire:model="keahlian_mentor" label="Keahlian" placeholder="Frontend Development, UI/UX"
                    rows="3" />
            </div>

            <!-- Buttons - Always at bottom -->
            <div class="space-y-3 pl-4 mt-auto pt-5">
                    <flux:button wire:click="saveMentor" class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                        Simpan Perubahan
                    </flux:button>

                    <div class="text-right">
                        <button wire:click="openMentorModal" type="button"
                            class="text-sm text-neutral-500 dark:text-neutral-400
                              hover:text-[#3526B3] hover:underline transition">
                            Lihat Detail →
                        </button>
                    </div>
            </div>
        </div>

    </div>

    <!-- MODAL DIVISI -->
    @if ($showDivisiModal)
        <flux:modal wire:model="showDivisiModal" class="w-[calc(100vw-2rem)] max-w-7xl space-y-6">
            <div>
                <flux:heading size="lg">Data Divisi</flux:heading>
                <flux:subheading>Daftar semua divisi yang tersedia</flux:subheading>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Divisi</th>
                            <th class="px-6 py-3">Deskripsi</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($divisiList as $index => $divisi)
                            <tr class="border-b dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium">{{ $divisi->nama_divisi }}</td>
                                <td class="px-6 py-4">{{ $divisi->deskripsi ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <flux:button wire:click="editDivisi({{ $divisi->id }})" size="sm"
                                            variant="ghost">
                                            Edit
                                        </flux:button>
                                        <flux:button wire:click="deleteDivisi({{ $divisi->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus divisi ini?" size="sm"
                                            variant="danger">
                                            Hapus
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-neutral-500">
                                    Belum ada data divisi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button wire:click="closeDivisiModal" variant="ghost">Tutup</flux:button>
            </div>
        </flux:modal>
    @endif

    <!-- MODAL SEKOLAH -->
    @if ($showSekolahModal)
        <flux:modal wire:model="showSekolahModal" class="w-[calc(100vw-2rem)] max-w-7xl space-y-6">
            <div>
                <flux:heading size="lg">Data Sekolah</flux:heading>
                <flux:subheading>Daftar semua sekolah yang terdaftar</flux:subheading>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Sekolah</th>
                            <th class="px-6 py-3">Alamat</th>
                            <th class="px-6 py-3">No. Telepon</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sekolahList as $index => $sekolah)
                            <tr class="border-b dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium">{{ $sekolah->nama_sekolah }}</td>
                                <td class="px-6 py-4">{{ $sekolah->alamat ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $sekolah->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <flux:button wire:click="editSekolah({{ $sekolah->id }})" size="sm"
                                            variant="ghost">
                                            Edit
                                        </flux:button>
                                        <flux:button wire:click="deleteSekolah({{ $sekolah->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus sekolah ini?" size="sm"
                                            variant="danger">
                                            Hapus
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-neutral-500">
                                    Belum ada data sekolah
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button wire:click="closeSekolahModal" variant="ghost">Tutup</flux:button>
            </div>
        </flux:modal>
    @endif

    <!-- MODAL MENTOR -->
    @if ($showMentorModal)
        <flux:modal wire:model="showMentorModal" class="w-[calc(100vw-2rem)] max-w-7xl space-y-6">
            <div>
                <flux:heading size="lg">Data Mentor</flux:heading>
                <flux:subheading>Daftar semua mentor yang terdaftar</flux:subheading>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-neutral-100 dark:bg-neutral-700">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Mentor</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">No. Telepon</th>
                            <th class="px-6 py-3">Divisi</th>
                            <th class="px-6 py-3">Keahlian</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mentorList as $index => $mentor)
                            <tr class="border-b dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium">{{ $mentor->nama_mentor }}</td>
                                <td class="px-6 py-4">{{ $mentor->email ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $mentor->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                        {{ $mentor->divisi->nama_divisi ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $mentor->keahlian ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <flux:button wire:click="editMentor({{ $mentor->id }})" size="sm"
                                            variant="ghost">
                                            Edit
                                        </flux:button>
                                        <flux:button wire:click="deleteMentor({{ $mentor->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus mentor ini?" size="sm"
                                            variant="danger">
                                            Hapus
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-neutral-500">
                                    Belum ada data mentor
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button wire:click="closeMentorModal" variant="ghost">Tutup</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
