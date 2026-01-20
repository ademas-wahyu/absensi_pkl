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
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()" class="bg-transparent!
                           text-neutral-800 dark:text-neutral-200" />

            <flux:menu class="w-48">
                <flux:menu.item icon="cog" :href="route('profile.edit')" wire:navigate>
                    Settings
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        Logout
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </div>
</flux:header>