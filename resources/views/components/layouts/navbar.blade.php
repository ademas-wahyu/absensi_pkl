<flux:header class="sticky top-0 z-40 h-14 w-full
           bg-white dark:bg-neutral-900
           border-b border-neutral-200 dark:border-neutral-700">

    <!-- LEFT : Sidebar Toggle -->
    <flux:sidebar.toggle icon="bars-3" class="p-2 rounded-md
               text-neutral-700 dark:text-neutral-200
               hover:bg-neutral-100 dark:hover:bg-neutral-800" />

    <!-- SPACER : Push remaining items to right -->
    <flux:spacer />

    <!-- RIGHT : Theme Toggle & Profile -->
    <div class="flex items-center gap-3">

        <!-- Toggle Dark / Light -->
        <button x-data="{ theme: localStorage.getItem('theme') ?? 'system' }" class="p-2 rounded-full
                       hover:bg-neutral-100 dark:hover:bg-neutral-800" @click="
                    if (theme === 'light') {
                        theme = 'dark';
                        document.documentElement.classList.add('dark');
                    } else {
                        theme = 'light';
                        document.documentElement.classList.remove('dark');
                    }
                    localStorage.setItem('theme', theme);
                ">

            <!-- Moon for light mode -->
            <flux:icon x-show="theme !== 'dark'" name="moon" class="h-5 w-5 text-neutral-700 dark:text-yellow-400" />

            <!-- Sun for dark mode -->
            <flux:icon x-show="theme === 'dark'" name="sun" class="h-5 w-5 text-yellow-400" />
        </button>

        <!-- Profile -->
        <flux:dropdown position="bottom" align="end">
            <button class="h-9 w-9 rounded-full flex items-center justify-center
                           bg-linear-to-br from-[#3526B3] to-[#8615D9]
                           text-white font-semibold text-sm
                           hover:opacity-90 transition-opacity
                           shadow-md focus:outline-none">
                {{ auth()->user()->initials() }}
            </button>

            <flux:menu class="w-56 shadow-xl border border-neutral-200 dark:border-neutral-700">
                <!-- User Info Header -->
                <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center
                                    bg-linear-to-br from-[#3526B3] to-[#8615D9]
                                    text-white font-semibold">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-neutral-800 dark:text-white truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="py-1">
                    <flux:menu.item icon="user" :href="route('profile.edit')" wire:navigate
                        class="text-neutral-700 dark:text-neutral-200
                               hover:bg-linear-to-r hover:from-[#3526B3] hover:to-[#8615D9]
                               hover:text-white transition-all">
                        Profile
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="text-red-600 dark:text-red-400
                                   hover:bg-red-50 dark:hover:bg-red-900/20
                                   hover:text-red-700 dark:hover:text-red-300
                                   transition-all w-full">
                            Logout
                        </flux:menu.item>
                    </form>
                </div>
            </flux:menu>
        </flux:dropdown>

    </div>
</flux:header>
