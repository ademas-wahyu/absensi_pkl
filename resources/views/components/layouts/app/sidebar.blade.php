<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="h-screen w-screen bg-slate-50 dark:bg-zinc-900 antialiased">
         <flux:sidebar sticky collapsible class="bg-gradient-to-b from-[#4b3bb8] via-[#7f5adf] to-[#c7b3ff] text-white border-r border-white/10 shadow-lg dark:from-neutral-900 dark:to-neutral-800 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                    href="#"
                    logo="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                    logo:dark="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSryxZgadX3g2ikgH9Be7U37CId6DEudtxjFw&s"
                    name="Jurnal Vodeco"
                    class="!text-white [&_*]:!text-white"
                />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2 !text-white [&_*]:!text-white hover:bg-white/10" />
        </flux:sidebar.header>

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            </a>
            
             <flux:sidebar.nav>
            <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="!text-white hover:text-white/80 [&_*]:!text-white data-[current]:!text-zinc-800 data-[current]:[&_*]:!text-zinc-800 transition">{{ __('Dashboard') }}</flux:sidebar.item>
            <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')" :current="request()->routeIs('absent_users')" wire:navigate class="!text-white hover:text-white/80 [&_*]:!text-white data-[current]:!text-zinc-800 data-[current]:[&_*]:!text-zinc-800 transition">{{ __('Absensi') }}</flux:sidebar.item>
            <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')" :current="request()->routeIs('jurnal_users')" wire:navigate class="!text-white hover:text-white/80 [&_*]:!text-white data-[current]:!text-zinc-800 data-[current]:[&_*]:!text-zinc-800 transition">{{ __('Isi Jurnal') }}</flux:sidebar.item>
        </flux:sidebar.nav>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                    class="!bg-white !text-zinc-800 [&_*]:!text-zinc-800 hover:!bg-white/90 p-2 rounded-lg"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-neutral-800 dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu (moved to bottom) -->
        

        <!-- Mobile User Menu (fixed bottom on small screens) - horizontal layout -->
        <flux:header class="lg:hidden fixed bottom-0 left-0 right-0 z-10 border-t border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="flex items-center justify-between px-3 py-2">
                <div class="flex items-center gap-3">

                    <flux:sidebar.nav class="flex flex-row items-center gap-1">
                        <flux:sidebar.item icon="layout-dashboard" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="text-neutral-800 dark:text-neutral-100 px-2 py-1">{{ __('Dashboard') }}</flux:sidebar.item>
                        <flux:sidebar.item icon="calendar-date-range" :href="route('absent_users')" :current="request()->routeIs('absent_users')" wire:navigate class="text-neutral-800 dark:text-neutral-100 px-2 py-1">{{ __('Absensi') }}</flux:sidebar.item>
                        <flux:sidebar.item icon="notebook-pen" :href="route('jurnal_users')" :current="request()->routeIs('jurnal_users')" wire:navigate class="text-neutral-800 dark:text-neutral-100 px-2 py-1">{{ __('Isi Jurnal') }}</flux:sidebar.item>
                    </flux:sidebar.nav>
                    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                </div>
            </div>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>