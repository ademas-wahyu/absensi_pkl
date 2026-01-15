<div>
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-neutral-700 hover:text-neutral-900 dark:text-neutral-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-5 h-5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali</span>
            </a>
        </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-neutral-700 dark:text-neutral-300">
            <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:text-neutral-400">
                <tr class="bg-neutral-200 text-left dark:bg-neutral-800">
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Nama</th>
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Asal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataAdmin as $dataAdmin)
                    <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $dataAdmin->name }}</td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $dataAdmin->asal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>