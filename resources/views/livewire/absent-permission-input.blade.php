<div>
    <flux:modal name="input-permission" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Input Izin</flux:heading>
                <p class="text-sm text-neutral-500 mt-1">Silahkan isi alasan izin Anda.</p>
            </div>

            @if($hasCheckedIn)
                <div
                    class="flex items-center gap-3 p-4 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" class="text-red-500 shrink-0">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" x2="12" y1="8" y2="12" />
                        <line x1="12" x2="12.01" y1="16" y2="16" />
                    </svg>
                    <p class="text-sm text-red-700 dark:text-red-300 font-medium">
                        Anda sudah absen masuk hari ini. Tidak bisa mengirim izin.
                    </p>
                </div>
                <div class="flex justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">Tutup</flux:button>
                    </flux:modal.close>
                </div>
            @else
                <form wire:submit="save" class="space-y-4">
                    <flux:radio.group wire:model="status" label="Status" variant="segmented">
                        <flux:radio value="Izin" label="Izin" />
                        <flux:radio value="Sakit" label="Sakit" />
                    </flux:radio.group>

                    <flux:textarea wire:model="reason" label="Alasan" placeholder="Contoh: Sakit, Urusan Keluarga, dll..."
                        required />

                    <div class="flex justify-end gap-2">
                        <flux:modal.close>
                            <flux:button variant="ghost">Batal</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="primary"
                            class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white!">Kirim Izin</flux:button>
                    </div>
                </form>
            @endif
        </div>
    </flux:modal>
</div>