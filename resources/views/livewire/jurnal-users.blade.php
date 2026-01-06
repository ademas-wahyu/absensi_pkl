<div>
    <div class="mb-4 flex justify-end">
        <flux:modal.trigger name="input-jurnal-user">
<flux:button
    class="
        bg-primary text-white
        inline-flex items-center gap-2
        hover:bg-primary/90
        active:scale-95
        transition-all duration-200 ease-out
        shadow-md hover:shadow-lg

        dark:bg-primary/80
        dark:hover:bg-primary
        dark:shadow-black/30
    ">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus w-5 h-5" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
    Input Jurnal
</flux:button>
        </flux:modal.trigger>
    </div>

<livewire:jurnal-user-input />

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
            <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:text-neutral-400">
                <tr class="bg-neutral-200 text-left dark:bg-neutral-800">
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Tanggal</th>
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Aktivitas</th>
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jurnalUsers as $jurnalUser)
                    <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $jurnalUser->jurnal_date }}</td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $jurnalUser->activity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>