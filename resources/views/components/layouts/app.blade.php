<x-layouts.app.sidebar :title="$title ?? null">
     @include('components.layouts.navbar')
    <flux:main>
       

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
