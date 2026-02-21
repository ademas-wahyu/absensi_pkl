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
    </div>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Card Header Divisi --}}
        <div
            class="bg-white dark:bg-neutral-800 shadow rounded-xl p-6 border-l-4 border-[#3526B3] dark:border-[#8615D9]">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-[#3526B3] dark:text-[#8615D9]">
                    <path d="M16 10h2" />
                    <path d="M16 14h2" />
                    <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                    <circle cx="9" cy="11" r="2" />
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                </svg>
                Divisi {{ $divisiName }}
            </h1>
            <p class="mt-3 text-neutral-600 dark:text-neutral-400 leading-relaxed">
                {{ $description }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card Mentor --}}
            <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-6">
                <h2
                    class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-4 flex items-center gap-2 border-b border-neutral-100 dark:border-neutral-700 pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-purple-600 dark:text-purple-400">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    Daftar Mentor
                </h2>

                @if($mentors && count($mentors) > 0)
                    <ul class="space-y-3">
                        @foreach($mentors as $mentor)
                            <li
                                class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50 dark:bg-neutral-700/50 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($mentor->nama_mentor, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100 truncate">
                                        {{ $mentor->nama_mentor }}
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $mentor->email ?? 'Email tidak tersedia' }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="py-8 text-center text-neutral-500 dark:text-neutral-400 flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="mb-2 opacity-50">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M19 8v6" />
                            <path d="M22 11h-6" />
                        </svg>
                        <p class="text-sm">Belum ada mentor di divisi ini.</p>
                    </div>
                @endif
            </div>

            {{-- Card Anak PKL --}}
            <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-6">
                <h2
                    class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-4 flex items-center gap-2 border-b border-neutral-100 dark:border-neutral-700 pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-blue-600 dark:text-blue-400">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Daftar Anak PKL
                </h2>

                @if($students && count($students) > 0)
                    <ul class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($students as $student)
                            <li
                                class="flex items-center gap-3 p-3 rounded-lg bg-neutral-50 dark:bg-neutral-700/50 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors">
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                                        <span class="truncate">{{ $student->name }}</span>
                                        @if($student->id === auth()->id())
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#3526B3] text-white tracking-wider uppercase">Anda</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                        {{ $student->sekolah ?? 'Sekolah tidak diatur' }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="py-8 text-center text-neutral-500 dark:text-neutral-400 flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="mb-2 opacity-50">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <p class="text-sm">Belum ada anak PKL di divisi ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
        }
    </style>
</div>