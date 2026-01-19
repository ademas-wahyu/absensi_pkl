<x-layouts.app :title="__('Jumlah Anak')">
  <div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('Jumlah Anak') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Jumlah anak yang terdaftar') }}</flux:subheading>
    <flux:separator variant="subtle" />
</div>
<livewire:data-admin />
</x-layouts.app>