<div>
    <flux:modal name="edit-jurnal" class="md:w-100">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Jurnal</flux:heading>
                <flux:text class="mt-2">Silahkan Isi Jurnal Anda</flux:text>
            </div>

            <flux:input label="Tanggal" type="date" wire:model="jurnal_date" />
            <flux:input label="Aktivitas" wire:model="activity" />
            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="submit" wire:loading.attr="disabled"
                    class="bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white! hover:opacity-90">
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading>Memproses...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
   @script
    <script>
        // Listener untuk membuka modal (sudah ada sebelumnya)
        window.addEventListener('show-edit-modal', () => {
            $flux.modal('edit-jurnal').show();
        });

        // Listener BARU untuk menutup modal
        window.addEventListener('close-edit-modal', () => {
            $flux.modal('edit-jurnal').hide();
        });
    </script>
    @endscript
</div>