<x-layouts.app :title="__('Absensi')">
    <div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Absensi') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Silahkan Isi Absensi Anda') }}</flux:subheading>
    <flux:separator variant="subtle" />
</div>

<livewire:absent-users />
</x-layouts.app>