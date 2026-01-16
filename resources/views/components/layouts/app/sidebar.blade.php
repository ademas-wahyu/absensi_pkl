<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="h-screen w-screen bg-slate-50 dark:bg-zinc-900 antialiased">

    <!-- SIDEBAR -->
    <flux:sidebar sticky collapsible class="bg-white text-zinc-700 border-r border-zinc-200 shadow-sm
               dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-200">

        <!-- HEADER -->
        <flux:sidebar.header>
            <flux:sidebar.brand href="{{ route('dashboard') }}"
                logo="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                logo:dark="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                name="Jurnal Vodeco" class="font-bold text-[#3526B3] dark:text-[#8615D9]" />

            <flux:sidebar.collapse class="hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg" />
        </flux:sidebar.header>

        <!-- NAVIGATION -->
        <flux:sidebar.nav>

            <!-- Dashboard -->
            <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')"
                :current="request()->routeIs('dashboard')" wire:navigate class="
                    text-zinc-700 dark:text-zinc-200
                    hover:text-[#3526B3] dark:hover:text-[#8615D9]

                    data-[current]:bg-gradient-to-r
                    data-[current]:from-[#3526B3]
                    data-[current]:to-[#8615D9]
                    data-[current]:text-white
                    data-[current]:shadow-md
                    data-[current]:rounded-lg

                    transition-all
                ">
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <!-- Absensi -->
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                :current="request()->routeIs('absent_users')" wire:navigate class="
                    text-zinc-700 dark:text-zinc-200
                    hover:text-[#3526B3] dark:hover:text-[#8615D9]

                    data-[current]:bg-gradient-to-r
                    data-[current]:from-[#3526B3]
                    data-[current]:to-[#8615D9]
                    data-[current]:text-white
                    data-[current]:shadow-md
                    data-[current]:rounded-lg

                    transition-all
                ">
                {{ __('Absensi') }}
            </flux:sidebar.item>

            <!-- Jurnal -->
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                :current="request()->routeIs('jurnal_users')" wire:navigate class="
                    text-zinc-700 dark:text-zinc-200
                    hover:text-[#3526B3] dark:hover:text-[#8615D9]

                    data-[current]:bg-gradient-to-r
                    data-[current]:from-[#3526B3]
                    data-[current]:to-[#8615D9]
                    data-[current]:text-white
                    data-[current]:shadow-md
                    data-[current]:rounded-lg

                    transition-all
                ">
                {{ __('Isi Jurnal') }}
            </flux:sidebar.item>

        </flux:sidebar.nav>

        <flux:spacer />

        <!-- PROFILE DESKTOP -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" class="
                    bg-gradient-to-r from-[#3526B3] to-[#8615D9]
                    text-white [&_*]:text-white
                    hover:opacity-90
                    p-2 rounded-xl shadow-md
                " />

            <flux:menu class="w-[220px]">
                <div class="px-2 py-2 flex items-center gap-2">
                    <span class="h-9 w-9 rounded-lg flex items-center justify-center
                        bg-gradient-to-br from-[#3526B3] to-[#8615D9] text-white font-semibold">
                        {{ auth()->user()->initials() }}
                    </span>

                    <div class="leading-tight">
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Settings') }}
                </flux:menu.item>

                <flux:menu.item 
                    icon="sun" 
                    onclick="
                        const html = document.documentElement;
                        const currentTheme = localStorage.getItem('theme') ?? 'system';
                        let newTheme;
                        if (currentTheme === 'light') {
                            newTheme = 'dark';
                            html.classList.add('dark');
                        } else {
                            newTheme = 'light';
                            html.classList.remove('dark');
                        }
                        localStorage.setItem('theme', newTheme);
                    "
                    class="cursor-pointer"
                >
                    {{ __('Toggle Theme') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- MOBILE BOTTOM NAV -->

    <!-- Mobile User Menu (fixed bottom on small screens) - horizontal layout -->
    <flux:header class="lg:hidden fixed bottom-0 inset-x-0 z-10
           bg-white dark:bg-neutral-900
           border-t border-neutral-200 dark:border-neutral-700
           shadow-sm">

        <flux:sidebar.nav class="flex flex-row items-center justify-around px-2 py-2">

            <!-- Dashboard -->
            <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')"
                :current="request()->routeIs('dashboard')" wire:navigate 
            class="
            inline-flex w-auto shrink-0
            flex-col items-center justify-center
            gap-3

            px-6 py-4
            text-[11px] font-medium

            text-neutral-500 dark:text-neutral-400
            data-[current]:text-[#3526B3]
            dark:data-[current]:text-[#8615D9]

            transition
            "
            >
            {{ __('Dashboard') }} 
           </flux:sidebar.item>

            <!-- Absensi -->
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')"
                :current="request()->routeIs('absent_users')" wire:navigate class="
                inline-flex w-auto shrink-0
                flex-col items-center justify-center
                gap-3

                px-6 py-4
                text-[11px] font-medium

                text-neutral-500 dark:text-neutral-400
                data-[current]:text-[#3526B3]
                dark:data-[current]:text-[#8615D9]

                transition
            ">
                {{ __('Absensi') }}
            </flux:sidebar.item>

            <!-- Isi Jurnal -->
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')"
                :current="request()->routeIs('jurnal_users')" wire:navigate class="
                inline-flex w-auto shrink-0
                flex-col items-center justify-center
                gap-3

                px-6 py-4
                text-[11px] font-medium

                text-neutral-500 dark:text-neutral-400
                data-[current]:text-[#3526B3]
                dark:data-[current]:text-[#8615D9]

                transition
            ">
               {{ __('Isi Jurnal') }}
            </flux:sidebar.item>

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