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
        <div class="flex items-center">
            <flux:modal.trigger name="input-absent-user">
                <flux:button class="
                   text-zinc-700 dark:text-zinc-200
                    hover:text-[#3526B3] dark:hover:text-[#8615D9]

                    data-[current]:bg-gradient-to-r
                    data-[current]:from-[#3526B3]
                    data-[current]:to-[#8615D9]
                    data-[current]:text-white
                    data-[current]:shadow-md
                    data-[current]:rounded-lg

                    transition-all
                ">
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
    </div>

    <livewire:absent-user-input />

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
                @foreach($absentUsers as $absentUser)
                    <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                            {{ $absentUser->absent_date }}
                        </td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $absentUser->status }}
                        </td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $absentUser->reason }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>