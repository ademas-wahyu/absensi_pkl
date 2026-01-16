<flux:header
    class="sticky top-0 z-40 h-14
           bg-white dark:bg-neutral-900
           border-b border-neutral-200 dark:border-neutral-700">

    <div class="flex items-center justify-between h-full px-4">

        <!-- LEFT : Sidebar Toggle -->
        <flux:sidebar.toggle
            icon="bars-3"
            class="p-2 rounded-md
                   text-neutral-700 dark:text-neutral-200
                   hover:bg-neutral-100 dark:hover:bg-neutral-800" />

        <!-- RIGHT : Menu -->
        <div class="flex items-center gap-3">

            <!-- Toggle Dark / Light -->
            <button
                class="p-2 rounded-full
                       hover:bg-neutral-100 dark:hover:bg-neutral-800"
                x-on:click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'">

                <!-- Moon -->
                <svg x-show="$flux.appearance === 'light'"
                     xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 text-neutral-700"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 12.79A9 9 0 1111.21 3
                             7 7 0 0021 12.79z"/>
                </svg>

                <!-- Sun -->
                <svg x-show="$flux.appearance === 'dark'"
                     xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 text-yellow-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v2m0 14v2m9-9h-2M5 12H3
                             m15.364-6.364l-1.414 1.414
                             M7.05 16.95l-1.414 1.414
                             m0-12.728l1.414 1.414
                             M16.95 16.95l1.414 1.414"/>
                </svg>
            </button>

            <!-- Profile -->
            <flux:dropdown position="bottom" align="end">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    class="!bg-transparent
                           text-neutral-800 dark:text-neutral-200" />

                <flux:menu class="w-48">
                    <flux:menu.item
                        icon="cog"
                        :href="route('profile.edit')"
                        wire:navigate>
                        Settings
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle">
                            Logout
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>

        </div>
    </div>
</flux:header>
