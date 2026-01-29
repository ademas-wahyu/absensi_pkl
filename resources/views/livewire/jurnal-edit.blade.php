<div>
    <flux:modal name="edit-jurnal" class="md:w-100">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit Jurnal</flux:heading>
            <flux:text class="mt-2">Silahkan Isi Jurnal Anda</flux:text>
        </div>

        <flux:input label="jurnal_date" type="date" wire:model="jurnal_date" />
        <flux:input label="activity" wire:model="activity" />
        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary" wire:click="submit" class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">Simpan</flux:button>
        </div>
    </div>
</flux:modal>
</div>