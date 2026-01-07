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
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">
                            <div class="inline-flex items-center gap-2">
                                <button type="button" wire:click="prepareEdit({{ $jurnalUser->id }})" class="text-blue-600 hover:text-blue-800 p-1 rounded" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#002aff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                </button>

                                <button type="button" onclick="confirm('Yakin ingin menghapus jurnal ini?') || event.stopImmediatePropagation()" wire:click="prepareDelete({{ $jurnalUser->id }})" class="text-red-600 hover:text-red-800 p-1 rounded" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    <script>
        document.addEventListener('jurnal-saved', function () {
            if (window.Livewire && typeof Livewire.emit === 'function') {
                Livewire.emit('refreshJurnalList');
            }
        });
    </script>