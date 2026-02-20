<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Manajemen Jadwal</h1>
        <p class="text-zinc-600 dark:text-zinc-400 mt-1">Kelola jadwal WFH/WFO/Libur untuk setiap anak PKL</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <flux:callout color="green" class="mb-4">
            {{ session('success') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout color="red" class="mb-4">
            {{ session('error') }}
        </flux:callout>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                        icon="magnifying-glass" clearable />
                </div>

                <!-- Filter Divisi -->
                <div>
                    <flux:select wire:model.live="filterDivisi" placeholder="Semua Divisi">
                        <flux:select.option value="">Semua Divisi</flux:select.option>
                        @foreach($this->divisiOptions as $divisi)
                            <flux:select.option value="{{ $divisi->nama_divisi }}">{{ $divisi->nama_divisi }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Filter Sekolah -->
                <div>
                    <flux:select wire:model.live="filterSekolah" placeholder="Semua Sekolah">
                        <flux:select.option value="">Semua Sekolah</flux:select.option>
                        @foreach($sekolahList as $sekolah)
                            <flux:select.option value="{{ $sekolah }}">{{ $sekolah }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <flux:button wire:click="resetFilters" variant="subtle" icon="x-mark" size="sm">
                    Reset
                </flux:button>

                <flux:dropdown>
                    <flux:button variant="subtle" size="sm" icon="ellipsis-horizontal" />
                    <flux:menu>
                        <flux:menu.item wire:click="downloadTemplate" icon="arrow-down-tray">Download Template CSV
                        </flux:menu.item>
                        <flux:menu.item wire:click="exportSchedules" icon="document-arrow-down">Export Jadwal Bulan Ini
                        </flux:menu.item>
                        <flux:menu.separator />
                        <flux:modal.trigger name="import-schedule">
                            <flux:menu.item icon="arrow-up-tray">Import Jadwal (CSV)</flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>
                </flux:dropdown>

                <flux:modal.trigger name="bulk-schedule">
                    <flux:button size="sm" variant="primary" icon="calendar-days">
                        Jadwal Massal
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="auto-schedule">
                    <flux:button size="sm" variant="primary" icon="sparkles">
                        Generate Otomatis
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Students List -->
        <div
            class="xl:col-span-1 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                <h2 class="font-semibold text-zinc-800 dark:text-white">Daftar Anak PKL</h2>
                <div class="flex items-center gap-2">
                    <div wire:loading
                        wire:target="search, filterDivisi, filterSekolah, selectUser, clearSelectedUser, previousMonth, nextMonth, goToToday"
                        class="text-sm text-zinc-500 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <flux:badge color="zinc" size="sm">{{ $students->total() }} siswa</flux:badge>
                </div>
            </div>

            <div class="divide-y divide-zinc-200 dark:divide-zinc-700 max-h-[600px] overflow-y-auto">
                @forelse($students as $student)
                    <div wire:click="selectUser({{ $student->id }})" wire:target="selectUser({{ $student->id }})"
                        wire:loading.class="opacity-50 pointer-events-none"
                        class="p-4 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors {{ $selectedUserId === $student->id ? 'bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-500' : '' }}">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                {{ $student->initials() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-zinc-800 dark:text-white truncate">{{ $student->name }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ $student->divisi }}</p>
                            </div>
                            @if($student->schedules->count() > 0)
                                <flux:badge color="indigo" size="sm">
                                    {{ $student->schedules->count() }} jadwal
                                </flux:badge>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                        <flux:icon name="users" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>Tidak ada siswa ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $students->links() }}
            </div>
        </div>

        <!-- Calendar View -->
        <div
            class="xl:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            @if($selectedUserId)
                @php
                    $selectedUser = \App\Models\User::find($selectedUserId);
                @endphp

                <!-- Calendar Header -->
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                {{ $selectedUser->initials() }}
                            </div>
                            <div>
                                <h2 class="font-semibold text-zinc-800 dark:text-white">{{ $selectedUser->name }}</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $selectedUser->divisi }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button wire:click="clearSelectedUser" size="sm" variant="subtle" icon="x-mark">
                                Tutup
                            </flux:button>
                        </div>
                    </div>
                </div>

                <!-- Calendar Navigation -->
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <flux:button wire:click="previousMonth" variant="subtle" size="sm" icon="chevron-left" />
                        <flux:button wire:click="nextMonth" variant="subtle" size="sm" icon="chevron-right" />
                        <flux:button wire:click="goToToday" variant="subtle" size="sm">
                            Hari Ini
                        </flux:button>
                    </div>
                    <h3 class="font-semibold text-zinc-800 dark:text-white">{{ $this->monthName }}</h3>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 hidden sm:block">Klik kotak: WFO → WFH → Libur →
                        Hapus</p>
                </div>

                <!-- Calendar Grid -->
                <div class="p-4">
                    <!-- Day Headers -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $idx => $day)
                            <div
                                class="text-center text-xs font-medium py-2 {{ $idx === 0 ? 'text-red-500 dark:text-red-400' : 'text-zinc-500 dark:text-zinc-400' }}">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-1">
                        @foreach($calendarDays as $dayInfo)
                            @if($dayInfo === null)
                                <div class="aspect-square"></div>
                            @else
                                @php
                                    $schedule = $this->getScheduleForDate($dayInfo['date']);
                                    $isToday = $dayInfo['isToday'];
                                    $isSunday = $dayInfo['isSunday'];

                                    // Tentukan warna berdasarkan jadwal atau hari Minggu
                                    if ($schedule) {
                                        $bgClass = match ($schedule->type) {
                                            'wfh' => 'bg-blue-50 dark:bg-blue-900/30',
                                            'wfo' => 'bg-green-50 dark:bg-green-900/30',
                                            'libur' => 'bg-red-100 dark:bg-red-900/40',
                                            default => 'bg-white dark:bg-zinc-800',
                                        };
                                        $textClass = match ($schedule->type) {
                                            'wfh' => 'text-blue-600 dark:text-blue-400',
                                            'wfo' => 'text-green-600 dark:text-green-400',
                                            'libur' => 'text-red-600 dark:text-red-400',
                                            default => 'text-zinc-500',
                                        };
                                    } elseif ($isSunday) {
                                        $bgClass = 'bg-red-50 dark:bg-red-900/20';
                                        $textClass = 'text-red-400 dark:text-red-500';
                                    } else {
                                        $bgClass = 'bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700';
                                        $textClass = 'text-zinc-500';
                                    }

                                    $borderClass = $isToday
                                        ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800'
                                        : ($isSunday && !$schedule ? 'border-red-200 dark:border-red-800/50' : 'border-zinc-200 dark:border-zinc-700');
                                @endphp
                                <div wire:click="quickSchedule({{ $selectedUserId }}, '{{ $dayInfo['date'] }}')"
                                    title="{{ $schedule ? strtoupper($schedule->type) : ($isSunday ? 'Minggu' : 'Klik untuk jadwal') }}"
                                    class="aspect-square p-1 border rounded-lg cursor-pointer transition-all hover:shadow-md {{ $borderClass }} {{ $bgClass }}">
                                    <div class="h-full flex flex-col items-center justify-center">
                                        <span
                                            class="text-sm font-medium {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : ($isSunday ? 'text-red-500 dark:text-red-400' : 'text-zinc-700 dark:text-zinc-300') }}">
                                            {{ $dayInfo['day'] }}
                                        </span>
                                        @if($schedule)
                                            <span class="mt-0.5 text-[10px] font-semibold uppercase {{ $textClass }}">
                                                {{ $schedule->type }}
                                            </span>
                                        @elseif($isSunday)
                                            <span class="mt-0.5 text-[10px] font-semibold uppercase text-red-400 dark:text-red-500">
                                                Libur
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Legend -->
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-3 h-3 rounded-sm bg-green-200 dark:bg-green-800 border border-green-400 dark:border-green-600">
                            </div>
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">WFO</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-3 h-3 rounded-sm bg-blue-200 dark:bg-blue-800 border border-blue-400 dark:border-blue-600">
                            </div>
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">WFH</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-3 h-3 rounded-sm bg-red-200 dark:bg-red-800 border border-red-400 dark:border-red-600">
                            </div>
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">Libur</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-3 h-3 rounded-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                            </div>
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">Minggu</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-2">💡 Klik kotak untuk siklus cepat: WFO → WFH
                        → Libur → Hapus</p>
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <flux:icon name="calendar" class="w-16 h-16 mx-auto mb-4 text-zinc-300 dark:text-zinc-600" />
                    <h3 class="text-lg font-medium text-zinc-800 dark:text-white mb-2">Pilih Siswa</h3>
                    <p class="text-zinc-500 dark:text-zinc-400">Klik salah satu siswa di sebelah kiri untuk melihat dan
                        mengelola jadwal mereka</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <flux:modal name="edit-schedule" class="max-w-md">
        <div class="p-6">
            <flux:heading size="lg">{{ $scheduleId ? 'Edit Jadwal' : 'Tambah Jadwal' }}</flux:heading>
            <flux:text class="mt-2">Atur jadwal kerja untuk tanggal tertentu</flux:text>

            <form wire:submit="save" class="mt-6 space-y-4">
                <flux:input wire:model="date" type="date" label="Tanggal" required />

                <flux:select wire:model="type" label="Tipe Jadwal" required>
                    <flux:select.option value="wfo">Work From Office (WFO)</flux:select.option>
                    <flux:select.option value="wfh">Work From Home (WFH)</flux:select.option>
                    <flux:select.option value="libur">Libur</flux:select.option>
                </flux:select>

                <flux:textarea wire:model="notes" label="Catatan" placeholder="Catatan tambahan (opsional)" rows="3" />

                @if($scheduleId)
                    <flux:error name="scheduleId" />
                @endif

                <div class="flex justify-between pt-4">
                    @if($scheduleId)
                        <flux:button wire:click="delete({{ $scheduleId }})" variant="danger" type="button">
                            Hapus
                        </flux:button>
                    @else
                        <div></div>
                    @endif

                    <div class="flex gap-2">
                        <flux:button type="button" wire:click="resetForm" data-slot="cancel">
                            Batal
                        </flux:button>
                        <flux:button variant="primary" type="submit">
                            Simpan
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Bulk Schedule Modal -->
    <flux:modal name="bulk-schedule" class="max-w-lg">
        <div class="p-6">
            <flux:heading size="lg">Jadwal Massal</flux:heading>
            <flux:text class="mt-2">Atur tanggal tertentu sebagai WFO, WFH, atau Libur massal untuk semua siswa
                sekaligus.</flux:text>

            <form wire:submit="saveBulk" class="mt-6 space-y-4">
                <!-- Pilih Divisi -->
                <flux:select wire:model="bulkDivisi" label="Divisi Target">
                    <flux:select.option value="">Semua Divisi (Seluruh Siswa)</flux:select.option>
                    @foreach($this->divisiOptions as $divisi)
                        <flux:select.option value="{{ $divisi->nama_divisi }}">{{ $divisi->nama_divisi }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="bulkStartDate" type="date" label="Tanggal Mulai" required />
                    <flux:input wire:model="bulkEndDate" type="date" label="Tanggal Selesai" required />
                </div>

                <flux:select wire:model="bulkType" label="Tipe Jadwal" required>
                    <flux:select.option value="wfo">Work From Office (WFO)</flux:select.option>
                    <flux:select.option value="wfh">Work From Home (WFH)</flux:select.option>
                    <flux:select.option value="libur">Libur</flux:select.option>
                </flux:select>

                <flux:textarea wire:model="bulkNotes" label="Catatan" placeholder="Catatan tambahan (opsional)"
                    rows="2" />

                <!-- Preview Dates -->
                @if(!empty($bulkDates))
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3 max-h-40 overflow-y-auto">
                        <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-2">
                            {{ count($bulkDates) }} tanggal akan dijadwalkan untuk
                            <strong>{{ $bulkDivisi ?: 'Semua Divisi' }}</strong>:
                        </p>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($bulkDates, 0, 15) as $dateInfo)
                                <flux:badge color="zinc" size="sm">
                                    {{ \Carbon\Carbon::parse($dateInfo['date'])->format('d M') }}
                                </flux:badge>
                            @endforeach
                            @if(count($bulkDates) > 15)
                                <flux:badge color="zinc" size="sm">+{{ count($bulkDates) - 15 }} lainnya</flux:badge>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button type="button" wire:click="resetBulkForm" data-slot="cancel">
                        Batal
                    </flux:button>
                    @if(empty($bulkDates))
                        <flux:button variant="filled" type="button" wire:click="generateBulkDates">
                            Preview
                        </flux:button>
                    @else
                        <flux:button variant="primary" type="submit">
                            Simpan {{ count($bulkDates) }} Jadwal
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Import Schedule Modal -->
    <flux:modal name="import-schedule" class="max-w-md">
        <div class="p-6">
            <flux:heading size="lg">Import Jadwal (CSV)</flux:heading>
            <flux:text class="mt-2">Unggah file CSV yang telah Anda isi sesuai dengan template. Format email harus valid
                dan terdaftar sebagai siswa.</flux:text>

            <form wire:submit="importSchedules" class="mt-6 space-y-4">
                <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                    x-on:livewire-upload-finish="uploading = false; progress = 0"
                    x-on:livewire-upload-error="uploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <flux:input type="file" wire:model="importFile" label="Pilih File CSV/TXT" accept=".csv, .txt"
                        required />

                    <!-- Progress Bar -->
                    <div x-show="uploading" class="w-full bg-zinc-200 rounded-full h-2.5 mt-2 dark:bg-zinc-700">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all"
                            x-bind:style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                @error('importFile')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button type="button" wire:click="$set('importFile', null)" data-slot="cancel">
                        Batal
                    </flux:button>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="importSchedules">Import Sekarang</span>
                        <span wire:loading wire:target="importSchedules">Mengimpor...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Auto-Generate Schedule Modal -->
    <flux:modal name="auto-schedule" class="max-w-lg">
        <div class="p-6" x-data="{ tab: 'manual' }">
            <flux:heading size="lg">Generate Jadwal Otomatis</flux:heading>
            <flux:text class="mt-2">Pilih mode Manual atau biarkan AI yang atur jadwal untuk Anda.</flux:text>

            <!-- Tab Switcher -->
            <div class="flex border-b border-zinc-200 dark:border-zinc-700 mt-4">
                <button type="button" @click="tab = 'manual'"
                    :class="tab === 'manual' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                    class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                    Manual
                </button>
                <button type="button" @click="tab = 'ai'"
                    :class="tab === 'ai' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                    class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                    AI (Groq)
                </button>
            </div>

            <!-- Shared: Divisi & Bulan -->
            <div class="mt-4 space-y-4">
                <flux:select wire:model="autoDivisi" label="Divisi Target">
                    <flux:select.option value="">Semua Divisi (Seluruh Siswa)</flux:select.option>
                    @foreach($this->divisiOptions as $divisi)
                        <flux:select.option value="{{ $divisi->nama_divisi }}">{{ $divisi->nama_divisi }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="autoMonth" label="Bulan">
                        @foreach(range(1, 12) as $m)
                            <flux:select.option value="{{ $m }}">
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:input wire:model="autoYear" type="number" label="Tahun" min="2024" max="2030" />
                </div>
            </div>

            <!-- Tab: Manual -->
            <form wire:submit="generateAutoSchedule" x-show="tab === 'manual'" x-cloak class="mt-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:input wire:model="autoWfoDays" type="number" label="Hari WFO / Minggu" min="0" max="6" />
                        <p class="text-[11px] text-green-600 dark:text-green-400 mt-1">Berangkat ke kantor</p>
                    </div>
                    <div>
                        <flux:input wire:model="autoWfhDays" type="number" label="Hari WFH / Minggu" min="0" max="6" />
                        <p class="text-[11px] text-blue-600 dark:text-blue-400 mt-1">Kerja dari rumah</p>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3 space-y-1.5">
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Preview per minggu:</p>
                    <div class="flex items-center gap-1 flex-wrap">
                        @for($i = 0; $i < $autoWfoDays; $i++)
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 dark:bg-green-900/40 text-[10px] font-bold text-green-700 dark:text-green-300 border border-green-300 dark:border-green-700">WFO</span>
                        @endfor
                        @for($i = 0; $i < $autoWfhDays; $i++)
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 dark:bg-blue-900/40 text-[10px] font-bold text-blue-700 dark:text-blue-300 border border-blue-300 dark:border-blue-700">WFH</span>
                        @endfor
                        @for($i = 0; $i < max(0, 6 - $autoWfoDays - $autoWfhDays); $i++)
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-50 dark:bg-red-900/20 text-[10px] font-bold text-red-500 dark:text-red-400 border border-red-200 dark:border-red-800">Libur</span>
                        @endfor
                        <span
                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 dark:bg-red-900/40 text-[10px] font-bold text-red-600 dark:text-red-400 border border-red-300 dark:border-red-700">Min</span>
                    </div>
                </div>

                @if($autoWfoDays + $autoWfhDays > 6)
                    <div class="text-sm text-red-600 dark:text-red-400 font-medium">⚠️ Total maks 6 hari!</div>
                @endif

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button type="button" wire:click="resetAutoForm" data-slot="cancel">Batal</flux:button>
                    <flux:button variant="primary" type="submit" :disabled="$autoWfoDays + $autoWfhDays > 6">
                        Generate Manual
                    </flux:button>
                </div>
            </form>

            <!-- Tab: AI -->
            <form wire:submit="generateAiSchedule" x-show="tab === 'ai'" x-cloak class="mt-4 space-y-4" x-data="{
                    templates: [
                        { label: '3 WFO + 2 WFH (Standar)', text: 'Buatkan jadwal 3 hari WFO dan 2 hari WFH per minggu untuk setiap siswa. Pastikan distribusi merata, tidak semua siswa WFO di hari yang sama. Sabtu sebagai WFH. Setiap hari minimal ada 50% siswa yang WFO agar kantor tidak kosong.' },
                        { label: '4 WFO + 1 WFH (Mayoritas Kantor)', text: 'Buatkan jadwal 4 hari WFO dan 1 hari WFH per minggu. Hari WFH untuk setiap siswa harus berbeda-beda agar kantor tetap terisi setiap hari. Sabtu WFO. Pastikan setiap siswa mendapat giliran WFH yang adil dan bergantian.' },
                        { label: '2 WFO + 3 WFH (Mayoritas Rumah)', text: 'Buatkan jadwal 2 hari WFO dan 3 hari WFH per minggu. Pastikan hari WFO setiap siswa tersebar merata agar setiap hari ada siswa yang masuk kantor. Sabtu WFH. Setiap siswa harus punya variasi jadwal yang berbeda.' },
                        { label: 'Bergantian (Shift A/B)', text: 'Bagi siswa menjadi 2 kelompok secara merata. Kelompok A WFO di Senin-Rabu, WFH Kamis-Jumat. Kelompok B WFH Senin-Rabu, WFO Kamis-Jumat. Sabtu bergantian WFO antar kelompok setiap minggu. Pastikan pembagian benar-benar adil.' },
                    ],
                    setPrompt(text) { $wire.set('aiPrompt', text); }
                }">
                <!-- Template Prompt -->
                <div>
                    <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-2">Pilih template atau tulis
                        sendiri:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="(tpl, i) in templates" :key="i">
                            <button type="button" @click="setPrompt(tpl.text)"
                                class="px-2.5 py-1 text-[11px] font-medium rounded-full border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors"
                                x-text="tpl.label"></button>
                        </template>
                    </div>
                </div>

                <div>
                    <flux:textarea wire:model="aiPrompt" label="Instruksi untuk AI" rows="4"
                        placeholder="Klik template di atas atau tulis instruksi sendiri..." />
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">🔒 Data pribadi siswa (nama, email)
                        TIDAK dikirim ke AI — hanya jumlah siswa dan parameter jadwal.</p>
                </div>

                <div
                    class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        <strong>Tips:</strong> Template sudah dioptimalkan agar distribusi merata. Anda bisa edit
                        setelah memilih template.
                    </p>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button type="button" wire:click="resetAutoForm" data-slot="cancel">Batal</flux:button>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="generateAiSchedule">🤖 Generate dengan AI</span>
                        <span wire:loading wire:target="generateAiSchedule">AI sedang berpikir...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>