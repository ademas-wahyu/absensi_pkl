<div>
    <flux:modal.trigger name="input-absent-user">
    <flux:button>Input Absensi</flux:button>
</flux:modal.trigger>

<livewire:absent-user-input />

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-neutral-300 dark:border-neutral-700">
            <thead>
                <tr class="bg-neutral-100 text-left dark:bg-neutral-800">
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Tanggal</th>
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Status</th>
                    <th class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">Alasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absentUsers as $absentUser)
                    <tr class="even:bg-neutral-50 dark:even:bg-neutral-700">
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $absentUser->absent_date }}</td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $absentUser->status }}</td>
                        <td class="border border-neutral-300 px-4 py-2 dark:border-neutral-700">{{ $absentUser->reason }}</td>
                    </tr>
                @endforeach
        </table>