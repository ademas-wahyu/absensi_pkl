<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Jadwal Saya</h1>
        <p class="text-zinc-600 dark:text-zinc-400 mt-1">Lihat jadwal WFH/WFO/Libur Anda</p>
    </div>

    <!-- Today's Schedule Card -->
    @php
        $todaySchedule = $this->todaySchedule;
    @endphp
    @if($todaySchedule)
        @php
            $todayBgClass = 'bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700';
            $todayIconBg = 'bg-zinc-100 dark:bg-zinc-700';
            $todayTextClass = 'text-zinc-700 dark:text-zinc-300';
            if ($todaySchedule->type === 'wfh') {
                $todayBgClass = 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800';
                $todayIconBg = 'bg-blue-100 dark:bg-blue-800';
                $todayTextClass = 'text-blue-700 dark:text-blue-300';
            } elseif ($todaySchedule->type === 'wfo') {
                $todayBgClass = 'bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800';
                $todayIconBg = 'bg-green-100 dark:bg-green-800';
                $todayTextClass = 'text-green-700 dark:text-green-300';
            } elseif ($todaySchedule->type === 'libur') {
                $todayBgClass = 'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800';
                $todayIconBg = 'bg-red-100 dark:bg-red-800';
                $todayTextClass = 'text-red-700 dark:text-red-300';
            }
        @endphp
        <div class="mb-6 p-4 rounded-xl {{ $todayBgClass }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $todayIconBg }}">
                        @if($todaySchedule->type === 'wfh')
                            <flux:icon name="home" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        @elseif($todaySchedule->type === 'wfo')
                            <flux:icon name="building-office" class="w-6 h-6 text-green-600 dark:text-green-400" />
                        @else
                            <flux:icon name="calendar-days" class="w-6 h-6 text-red-600 dark:text-red-400" />
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Jadwal Hari Ini</p>
                        <p class="font-semibold {{ $todayTextClass }}">
                            {{ $todaySchedule->getTypeLabel() }}
                        </p>
                    </div>
                </div>
                @if($todaySchedule->notes)
                    <div class="text-sm text-zinc-600 dark:text-zinc-400 max-w-xs truncate">
                        <span class="font-medium">Catatan:</span> {{ $todaySchedule->notes }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800">
            <div class="flex items-center gap-3">
                <flux:icon name="exclamation-triangle" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                <div>
                    <p class="font-medium text-amber-700 dark:text-amber-300">Belum ada jadwal untuk hari ini</p>
                    <p class="text-sm text-amber-600 dark:text-amber-400">Hubungi admin untuk mengatur jadwal Anda</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Calendar -->
        <div class="xl:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
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
                <div class="w-24"></div>
            </div>

            <!-- Calendar Grid -->
            <div class="p-4">
                <!-- Day Headers -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                        <div class="text-center text-xs font-medium text-zinc-500 dark:text-zinc-400 py-2">
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
                                $isSelected = $selectedDate === $dayInfo['date'];
                                $bgClass = 'bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700';
                                $textClass = 'text-zinc-500';
                                if ($schedule) {
                                    if ($schedule->type === 'wfh') {
                                        $bgClass = 'bg-blue-50 dark:bg-blue-900/30';
                                        $textClass = 'text-blue-600 dark:text-blue-400';
                                    } elseif ($schedule->type === 'wfo') {
                                        $bgClass = 'bg-green-50 dark:bg-green-900/30';
                                        $textClass = 'text-green-600 dark:text-green-400';
                                    } elseif ($schedule->type === 'libur') {
                                        $bgClass = 'bg-red-50 dark:bg-red-900/30';
                                        $textClass = 'text-red-600 dark:text-red-400';
                                    }
                                }
                            @endphp
                            <div
                                wire:click="selectDate('{{ $dayInfo['date'] }}')"
                                class="aspect-square p-1 border rounded-lg cursor-pointer transition-all hover:shadow-md {{ $isToday ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : 'border-zinc-200 dark:border-zinc-700' }} {{ $isSelected ? 'ring-2 ring-indigo-500' : '' }} {{ $bgClass }}"
                            >
                                <div class="h-full flex flex-col items-center justify-center">
                                    <span class="text-sm font-medium {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : 'text-zinc-700 dark:text-zinc-300' }}">
                                        {{ $dayInfo['day'] }}
                                    </span>
                                    @if($schedule)
                                        <span class="mt-1 text-[10px] font-medium uppercase {{ $textClass }}">
                                            {{ $schedule->type }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Legend -->
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-700"></div>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">WFO</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-blue-100 dark:bg-blue-900/50 border border-blue-300 dark:border-blue-700"></div>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">WFH</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-700"></div>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">Libur</span>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                <h3 class="font-semibold text-zinc-800 dark:text-white mb-4">Statistik Bulan Ini</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                        <div class="flex items-center gap-2">
                            <flux:icon name="building-office" class="w-5 h-5 text-green-600 dark:text-green-400" />
                            <span class="text-sm text-green-700 dark:text-green-300">WFO</span>
                        </div>
                        <span class="font-bold text-green-700 dark:text-green-300">{{ $statistics['wfo'] }} hari</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <div class="flex items-center gap-2">
                            <flux:icon name="home" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                            <span class="text-sm text-blue-700 dark:text-blue-300">WFH</span>
                        </div>
                        <span class="font-bold text-blue-700 dark:text-blue-300">{{ $statistics['wfh'] }} hari</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/30 rounded-lg">
                        <div class="flex items-center gap-2">
                            <flux:icon name="calendar-days" class="w-5 h-5 text-red-600 dark:text-red-400" />
                            <span class="text-sm text-red-700 dark:text-red-300">Libur</span>
                        </div>
                        <span class="font-bold text-red-700 dark:text-red-300">{{ $statistics['libur'] }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Selected Date Detail -->
            @if($selectedDate && $selectedSchedule)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-zinc-800 dark:text-white">Detail Jadwal</h3>
                        <flux:button wire:click="closeDetail" size="sm" variant="subtle" icon="x-mark" />
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Tanggal</p>
                            <p class="font-medium text-zinc-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Tipe</p>
                            <flux:badge color="{{ $selectedSchedule->getTypeColor() }}" size="sm">
                                {{ $selectedSchedule->getTypeLabel() }}
                            </flux:badge>
                        </div>
                        @if($selectedSchedule->notes)
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Catatan</p>
                                <p class="text-zinc-800 dark:text-white">{{ $selectedSchedule->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($selectedDate)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-zinc-800 dark:text-white">Detail Tanggal</h3>
                        <flux:button wire:click="closeDetail" size="sm" variant="subtle" icon="x-mark" />
                    </div>
                    <div class="text-center py-4">
                        <flux:icon name="calendar" class="w-12 h-12 mx-auto mb-2 text-zinc-300 dark:text-zinc-600" />
                        <p class="font-medium text-zinc-800 dark:text-white mb-1">
                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Belum ada jadwal untuk tanggal ini
                        </p>
                    </div>
                </div>
            @endif

            <!-- Upcoming Schedules -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
                <h3 class="font-semibold text-zinc-800 dark:text-white mb-4">Jadwal Mendatang</h3>
                <div class="space-y-2">
                    @forelse($this->upcomingSchedules as $schedule)
                        @php
                            $iconBg = 'bg-zinc-100 dark:bg-zinc-700';
                            $iconClass = 'text-zinc-600 dark:text-zinc-400';
                            if ($schedule->type === 'wfh') {
                                $iconBg = 'bg-blue-100 dark:bg-blue-800';
                                $iconClass = 'text-blue-600 dark:text-blue-400';
                            } elseif ($schedule->type === 'wfo') {
                                $iconBg = 'bg-green-100 dark:bg-green-800';
                                $iconClass = 'text-green-600 dark:text-green-400';
                            } elseif ($schedule->type === 'libur') {
                                $iconBg = 'bg-red-100 dark:bg-red-800';
                                $iconClass = 'text-red-600 dark:text-red-400';
                            }
                        @endphp
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $iconBg }}">
                                    @if($schedule->type === 'wfh')
                                        <flux:icon name="home" class="w-4 h-4 {{ $iconClass }}" />
                                    @elseif($schedule->type === 'wfo')
                                        <flux:icon name="building-office" class="w-4 h-4 {{ $iconClass }}" />
                                    @else
                                        <flux:icon name="calendar-days" class="w-4 h-4 {{ $iconClass }}" />
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-800 dark:text-white">
                                        {{ $schedule->date->translatedFormat('d M') }}
                                    </p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $schedule->date->translatedFormat('l') }}</p>
                                </div>
                            </div>
                            <flux:badge color="{{ $schedule->getTypeColor() }}" size="sm">
                                {{ strtoupper($schedule->type) }}
                            </flux:badge>
                        </div>
                    @empty
                        <div class="text-center py-4 text-zinc-500 dark:text-zinc-400">
                            <p class="text-sm">Tidak ada jadwal mendatang</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
