<x-layouts.app :title="__('Absensi')">
    <div class="relative mb-6 w-full">
        <div class="flex items-center gap-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="w-9 h-9 text-primary dark:text-gray-300 shrink-0">
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <rect width="18" height="18" x="3" y="4" rx="2" />
                <path d="M3 10h18" />
                <path d="M8 14h.01" />
                <path d="M12 14h.01" />
                <path d="M16 14h.01" />
                <path d="M8 18h.01" />
                <path d="M12 18h.01" />
                <path d="M16 18h.01" />
            </svg>
            <flux:heading size="xl" level="1">{{ __('Absensi') }}</flux:heading>
        </div>

        <!--murid-->
        @role('murid')
        <flux:subheading size="lg" class="mb-3">{{ __('Silahkan Isi Absensi Anda') }}</flux:subheading>
        @endrole

        <!--admin-->
        @role('admin')
        <flux:subheading size="lg" class="mb-3">{{ __('Data Absensi PKL') }}</flux:subheading>
        @endrole
    </div>
    <livewire:absent-users />
</x-layouts.app>