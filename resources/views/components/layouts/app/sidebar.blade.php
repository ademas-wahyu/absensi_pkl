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

            {{-- Jadwal (untuk semua role) --}}
            <flux:sidebar.item icon="calendar" :href="route('jadwal')"
                :current="request()->routeIs('jadwal')" wire:navigate class="{{ $navItemClasses }}">
                {{ __('Jadwal') }}
            </flux:sidebar.item>

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

            <flux:menu class="w-55">
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

    <!-- MOBILE FLOATING BUTTON - Tombol Absen dengan Toggle -->
    @unless(request()->routeIs('absent_users'))
        <div id="mobile-absen-container" class="lg:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50">

            <!-- Tombol Absen (Expanded State) -->
            <div id="absen-btn-expanded" class="transition-all duration-300 ease-in-out">
                <div class="relative">
                    <flux:modal.trigger name="input-absent-user">
                        <button class="flex flex-col items-center justify-center gap-1.5
                                    px-8 py-4
                                    bg-linear-to-r from-[#3526B3] to-[#8615D9]
                                    hover:from-[#2a1f8f] hover:to-[#6b11ab]
                                    text-white
                                    rounded-full
                                    shadow-2xl
                                    transition-all
                                    active:scale-95
                                    hover:shadow-[0_20px_50px_rgba(53,38,179,0.5)] cursor-pointer">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                            </svg>
                            <span class="text-sm font-bold">Absen</span>
                        </button>
                    </flux:modal.trigger>

                    <!-- Tombol Hide (Panah Bawah) -->
                    <button id="hide-absen-btn" class="absolute -top-2 -right-2
                                       w-8 h-8
                                       bg-red-500 hover:bg-red-600
                                       text-white
                                       rounded-full
                                       shadow-lg
                                       flex items-center justify-center
                                       transition-all
                                       active:scale-95" onclick="toggleAbsenButton()">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Show (Collapsed State - Hidden by default) -->
            <button id="show-absen-btn" class="w-12 h-12
                               bg-linear-to-r from-[#3526B3] to-[#8615D9]
                               hover:from-[#2a1f8f] hover:to-[#6b11ab]
                               text-white
                               rounded-full
                               shadow-2xl
                               flex items-center justify-center
                               transition-all
                               active:scale-95
                               hover:shadow-[0_20px_50px_rgba(53,38,179,0.5)]" onclick="toggleAbsenButton()">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
            </button>
        </div>
    @endunless

    {{ $slot }}

    <livewire:absent-user-input />
    @fluxScripts
    @include('partials.sweetalert')

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

        // Toggle manual tombol absen
        function toggleAbsenButton() {
            const expandedBtn = document.getElementById('absen-btn-expanded');
            const showBtn = document.getElementById('show-absen-btn');

            if (expandedBtn && showBtn) {
                if (expandedBtn.classList.contains('hidden')) {
                    // Show expanded button
                    expandedBtn.classList.remove('hidden');
                    showBtn.classList.add('hidden');

                    // Simpan state ke localStorage
                    localStorage.setItem('absenButtonState', 'expanded');

                    // Animation
                    expandedBtn.style.opacity = '0';
                    expandedBtn.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        expandedBtn.style.transition = 'all 0.3s ease-in-out';
                        expandedBtn.style.opacity = '1';
                        expandedBtn.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    // Hide expanded button, show small button
                    expandedBtn.style.opacity = '0';
                    expandedBtn.style.transform = 'translateY(20px)';

                    // Simpan state ke localStorage
                    localStorage.setItem('absenButtonState', 'collapsed');

                    setTimeout(() => {
                        expandedBtn.classList.add('hidden');
                        showBtn.classList.remove('hidden');

                        // Animation for show button
                        showBtn.style.opacity = '0';
                        showBtn.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            showBtn.style.transition = 'all 0.3s ease-in-out';
                            showBtn.style.opacity = '1';
                            showBtn.style.transform = 'scale(1)';
                        }, 10);
                    }, 300);
                }
            }
        }

        // Load state dari localStorage saat page load
        function loadAbsenButtonState() {
            const expandedBtn = document.getElementById('absen-btn-expanded');
            const showBtn = document.getElementById('show-absen-btn');
            const savedState = localStorage.getItem('absenButtonState');

            if (expandedBtn && showBtn) {
                if (savedState === 'collapsed') {
                    // Set ke collapsed state tanpa animasi
                    expandedBtn.classList.add('hidden');
                    showBtn.classList.remove('hidden');
                    showBtn.style.opacity = '1';
                    showBtn.style.transform = 'scale(1)';
                } else {
                    // Default expanded state
                    expandedBtn.classList.remove('hidden');
                    showBtn.classList.add('hidden');
                    expandedBtn.style.opacity = '1';
                    expandedBtn.style.transform = 'translateY(0)';
                }
            }
        }

        // Load state saat pertama kali dan saat navigasi Livewire
        loadAbsenButtonState();
        document.addEventListener('livewire:navigated', loadAbsenButtonState);
    </script>
</body>

</html>