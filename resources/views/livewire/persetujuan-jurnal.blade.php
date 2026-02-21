<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">Persetujuan Edit Jurnal</h2>
        <p class="text-sm text-neutral-500 dark:text-neutral-400">Daftar jurnal murid yang menunggu persetujuan
            perubahan.</p>
    </div>

    <div
        class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="text-xs text-neutral-600 dark:text-neutral-400 uppercase bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="px-6 py-4">Murid</th>
                        <th class="px-6 py-4">Jurnal Lama</th>
                        <th class="px-6 py-4 text-[#3526B3] dark:text-[#8615D9]">Jurnal Baru (Draft)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($pendingJurnals as $jurnal)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-medium text-neutral-900 dark:text-neutral-100">{{ $jurnal->user->name }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $jurnal->user->email }}</p>
                            </td>
                            <td class="px-6 py-4 min-w-[250px]">
                                <div
                                    class="bg-neutral-100 dark:bg-neutral-900 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700">
                                    <div
                                        class="mb-1 text-xs font-semibold text-neutral-500 dark:text-neutral-400 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($jurnal->jurnal_date)->format('d M Y') }}
                                    </div>
                                    <p class="text-neutral-700 dark:text-neutral-300">{{ $jurnal->activity }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 min-w-[250px]">
                                <div
                                    class="bg-blue-50 dark:bg-[#3526B3]/10 p-3 rounded-lg border border-blue-200 dark:border-[#3526B3]/30">
                                    <div
                                        class="mb-1 text-xs font-semibold text-blue-600 dark:text-[#8615D9] flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($jurnal->pending_jurnal_date)->format('d M Y') }}
                                    </div>
                                    <p class="text-neutral-800 dark:text-neutral-200">{{ $jurnal->pending_activity }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="approve({{ $jurnal->id }})"
                                        onclick="confirm('Setujui perubahan ini?') || event.stopImmediatePropagation()"
                                        class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors shadow-sm"
                                        title="Setujui">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Setuju
                                    </button>
                                    <button wire:click="reject({{ $jurnal->id }})"
                                        onclick="confirm('Tolak perubahan ini?') || event.stopImmediatePropagation()"
                                        class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors shadow-sm"
                                        title="Tolak">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div
                                    class="flex flex-col items-center justify-center text-neutral-500 dark:text-neutral-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="mb-3 opacity-50">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    <p class="text-base font-medium">Tidak ada persetujuan tertunda</p>
                                    <p class="text-sm mt-1">Semua perubahan jurnal telah diproses.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingJurnals->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                {{ $pendingJurnals->links() }}
            </div>
        @endif
    </div>
</div>