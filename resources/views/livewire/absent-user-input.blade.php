<div>
    <flux:modal name="input-absent-user" class="md:w-100">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Input Absensi</flux:heading>
            <flux:text class="mt-2">Silahkan Isi Absensi Anda</flux:text>
        </div>
        <flux:camera-input label="Foto Selfie" wire:model="selfie_photo" />
        <flux:input label="Tanggal Absensi" type="date" wire:model="absent_date" />
        <flux:select label="Status" wire:model="status">
            <option value="" disabled selected>Pilih Status</option>
            <option value="Hadir">Hadir</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin">Izin</option>
            <option value="Alpha">Alpha</option>
        </flux:select>
        <flux:textarea label="Alasan" wire:model="reason" />

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary" wire:click="submit" class="bg-blue">Simpan</flux:button>
        </div>
    </div>
</flux:modal>
</div>