 
 <x-layouts.app.sidebar :title="$title ?? null">
    
    <flux:main>
        <navbar />
        {{ $slot }}
        
    </flux:main>
</x-layouts.app.sidebar>
