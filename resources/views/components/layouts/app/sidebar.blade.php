<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="h-fit-screen w-full overflow-x-hidden bg-slate-50 dark:bg-zinc-900 antialiased">
    @include('components.layouts.navbar')

    <!-- SIDEBAR -->
    <flux:sidebar sticky collapsible class="h-screen sticky top-0 bg-white text-zinc-700 border-r border-zinc-200 shadow-sm
               dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-200">

        <!-- HEADER -->
        <flux:sidebar.header>
            <flux:sidebar.brand href="{{ route('dashboard') }}"
                logo="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                logo:dark="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                name="Jurnal Vodeco" class="font-bold text-[#3526B3] dark:text-[#8615D9]" />

            <flux:sidebar.collapse class="hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg" />
        </flux:sidebar.header>

        @php
            $navItemClasses = '
                text-zinc-700 dark:text-zinc-200
                hover:text-white dark:hover:text-white
                hover:!text-white dark:hover:!text-white
                hover:bg-linear-to-r hover:from-[#3526B3] hover:to-[#8615D9]
                hover:shadow-md hover:rounded-lg

                data-current:bg-linear-to-r
                data-current:from-[#3526B3]
                data-current:to-[#8615D9]
                data-current:text-white
                data-current:shadow-md
                data-current:rounded-lg

                transition-all
            ';
        @endphp

        <!-- NAVIGATION -->
        <flux:sidebar.nav>
            {{-- Dashboard untuk semua role --}}
            <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')"
                :current="request()->routeIs('dashboard')" wire:navigate class="{{ $navItemClasses }}">
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <!--Progress admin-->
            @role('admin')
            <flux:sidebar.group expandable heading="Progress" icon="file-badge" class="grid">
                <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                    :current="request()->routeIs('absent_users')" wire:navigate class="{{ $navItemClasses }}">
                    {{ __('Absensi') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                    :current="request()->routeIs('jurnal_users')" wire:navigate class="{{ $navItemClasses }}">
                    {{ __('Isi Jurnal') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endrole


            <!--Absent murid-->
            @role('murid')
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                :current="request()->routeIs('absent_users')" wire:navigate class="{{ $navItemClasses }}">
                {{ __('Absensi') }}
            </flux:sidebar.item>

            <!-- Jurnal (untuk semua) -->
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                :current="request()->routeIs('jurnal_users')" wire:navigate class="{{ $navItemClasses }}">
                {{ __('Isi Jurnal') }}
            </flux:sidebar.item>
            @endrole

            {{-- Daftar Anak PKL (khusus Admin) --}}
            @role('admin')
            <flux:sidebar.item icon="users" :href="route('jumlah_anak')" :current="request()->routeIs('jumlah_anak')"
                wire:navigate class="{{ $navItemClasses }}">
                {{ __('Daftar Anak PKL') }}
            </flux:sidebar.item>
            @endrole

            {{-- Divisi untuk Murid --}}
            @role('murid')
            <flux:sidebar.item icon="id-card" :href="route('divisi_users')"
                :current="request()->routeIs('divisi_users')" wire:navigate class="{{ $navItemClasses }}">
                {{ __('Divisi') }}
            </flux:sidebar.item>
            @endrole

            @role('admin')
            <flux:sidebar.item icon="settings" :href="route('setting')" :current="request()->routeIs('setting')"
                wire:navigate class="{{ $navItemClasses }}">
                {{ __('Settings') }}
            </flux:sidebar.item>
            @endrole
        </flux:sidebar.nav>

        <flux:spacer />

        <!-- PROFILE DESKTOP -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" class="
                    bg-linear-to-r from-[#3526B3] to-[#8615D9]
                    text-white **:text-white
                    hover:opacity-90
                    p-2 rounded-xl shadow-md
                " />

            @php
                $profileMenuClasses = '
                    text-zinc-700 dark:text-zinc-200
                    hover:bg-linear-to-r hover:from-[#3526B3] hover:to-[#8615D9]
                    hover:text-white dark:hover:text-white hover:!text-white dark:hover:!text-white
                    rounded-md
                    transition-colors
                ';
            @endphp

            <flux:menu class="w-[220px]">
                <div class="px-2 py-2 flex items-center gap-2">
                    <span class="h-9 w-9 rounded-lg flex items-center justify-center
                        bg-linear-to-br from-[#3526B3] to-[#8615D9] text-white font-semibold">
                        {{ auth()->user()->initials() }}
                    </span>

                    <div class="leading-tight">
                        <p class="font-semibold dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate
                    class="{{ $profileMenuClasses }}">
                    {{ __('Profile') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="{{ $profileMenuClasses }}">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- MOBILE BOTTOM NAV -->

    <!-- Mobile User Menu (fixed bottom on small screens) - horizontal layout -->
    <flux:header class="lg:hidden fixed bottom-0 inset-x-0 z-10
           bg-white dark:bg-neutral-900 text-bottom-0
           shadow-sm">

        <flux:sidebar.nav class="flex flex-row items-center justify-around px-2 py-2">

            {{-- Dashboard untuk semua role --}}
            <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')"
                :current="request()->routeIs('dashboard')" wire:navigate class="
           flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            @role('admin')

            {{-- Absensi --}}
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                :current="request()->routeIs('absent_users')" wire:navigate class="flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Absensi') }}
            </flux:sidebar.item>

            {{-- Jurnal --}}
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                :current="request()->routeIs('jurnal_users')" wire:navigate class="flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Jurnal') }}
            </flux:sidebar.item>
            @endrole


            <!-- Absensi (murid) -->
            @role('murid')
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                :current="request()->routeIs('absent_users')" wire:navigate class="
                flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Absensi') }}
            </flux:sidebar.item>

            <!-- Isi Jurnal (murid) -->
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                :current="request()->routeIs('jurnal_users')" wire:navigate class="
                flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Isi Jurnal') }}
            </flux:sidebar.item>
            @endrole

            {{-- Divisi untuk Murid --}}

            @role('murid')
            <flux:sidebar.item icon="id-card" :href="route('divisi_users')"
                :current="request()->routeIs('divisi_users')" wire:navigate class="
            flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Divisi') }}
            </flux:sidebar.item>
            @endrole

            {{-- Daftar Anak PKL (khusus Admin) --}}
            @role('admin')
            {{-- Daftar Anak PKL --}}
            <flux:sidebar.item icon="users" :href="route('jumlah_anak')" :current="request()->routeIs('jumlah_anak')"
                wire:navigate class="flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Daftar Anak PKL') }}
            </flux:sidebar.item>

           <!-- Settings (admin) -->
            <flux:sidebar.item icon="settings" :href="route('setting')" :current="request()->routeIs('setting')"
                wire:navigate class="
                    flex flex-col items-center justify-center gap-1
           px-3 py-2
           text-[10px] font-medium
           text-neutral-500 dark:text-neutral-400
           data-current:text-[#3526B3]
           dark:data-current:text-[#8615D9]
           transition">
                {{ __('Settings') }}
            </flux:sidebar.item>

            @endrole

        </flux:sidebar.nav>
    </flux:header>

    {{ $slot }}
    @fluxScripts

    <script>
        // Fungsi untuk mengatur tema
        function applyTheme() {
            const theme = localStorage.getItem('theme') ?? 'system';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Terapkan tema saat load
        applyTheme();

        // Dengarkan perubahan localStorage (untuk cross-tab atau jika diperlukan)
        window.addEventListener('storage', (e) => {
            if (e.key === 'theme') {
                applyTheme();
            }
        });

        // Jika menggunakan Livewire navigasi, pastikan tema diterapkan setelah navigasi
        document.addEventListener('livewire:navigated', applyTheme);
    </script>
</body>

</html>