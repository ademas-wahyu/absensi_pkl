<x-layouts.app :title="__('Daftar Anak PKL')">
  <div class="relative mb-6 w-full">
    <div class="flex items-center gap-3 mb-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="text-[#3526B3] dark:text-[#8615D9]">
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
      </svg>
      <flux:heading size="xl" level="1">{{ __('Daftar Anak PKL') }}</flux:heading>
    </div>
    <flux:subheading size="lg" class="mb-6">{{ __('Daftar lengkap anak PKL/Magang yang terdaftar') }}</flux:subheading>
    <flux:separator variant="subtle" />
  </div>
  <livewire:data-admin />
</x-layouts.app>