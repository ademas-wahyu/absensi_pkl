<div>
    <flux:modal name="input-jurnal-user" class="md:w-100">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Input Jurnal</flux:heading>
            <flux:text class="mt-2">Silahkan Isi Jurnal Anda</flux:text>
        </div>

        <flux:input label="Tanggal" type="date" wire:model="jurnal_date" />
        <flux:input label="Aktivitas" wire:model="activity" />
        <div class="flex">
            <flux:spacer />

            <flux:button
                type="submit"
                wire:click="save"
                variant="primary"
                class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white hover:opacity-90"
            >
                Simpan
            </flux:button>
        </div>
    </div>
</flux:modal>
</div>