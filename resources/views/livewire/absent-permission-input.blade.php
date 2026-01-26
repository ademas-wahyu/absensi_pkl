<div>
    <flux:modal name="input-permission" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Input Izin</flux:heading>
                <p class="text-sm text-neutral-500 mt-1">Silahkan isi alasan izin Anda.</p>
            </div>

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
        </div>
    </flux:modal>
</div>