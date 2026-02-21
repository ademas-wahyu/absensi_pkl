<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased">
    {{-- Mobile Layout --}}
    <div class="flex min-h-dvh flex-col lg:hidden">
        {{-- Mobile: Header gradient ungu-biru --}}
        <div class="login-gradient-header flex flex-col items-center justify-center px-6 py-12">
            <div class="relative z-10 flex flex-col items-center gap-3">
                <img src="{{ asset('icon-vodeco-white.svg') }}" alt="Vodeco" class="h-16 w-auto drop-shadow-lg">
                <span
                    class="text-sm font-semibold tracking-wider text-white/90 uppercase">{{ config('app.name', 'Vodeco') }}</span>
            </div>
        </div>

        {{-- Mobile: Form content --}}
        <div class="flex flex-1 items-start justify-center px-8 pt-8 pb-6">
            <div class="w-full max-w-[350px]">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Desktop Layout (lg+) --}}
    <div class="relative hidden h-dvh lg:grid lg:grid-cols-2">
        {{-- Desktop: Panel kiri dengan background --}}
        <div class="bg-muted relative flex h-full flex-col p-10 text-white">
            <div class="absolute inset-0" style="background-image: url('/auth-bg.png'); 
                    background-size: cover;
                    background-position: center;">
            </div>

            <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                <span class="flex h-10 w-10 items-center justify-center rounded-md">
                    <x-app-logo-icon class="me-2 h-7 fill-current text-white" />
                </span>
                {{ config('app.name', 'Laravel') }}
            </a>

            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <flux:heading size="lg" class="text-white">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                    <footer>
                        <flux:heading class="text-white">{{ trim($author) }}</flux:heading>
                    </footer>
                </blockquote>
            </div>
        </div>

        {{-- Desktop: Panel kanan (form) --}}
        <div class="flex items-center justify-center p-8">
            <div class="w-full max-w-[350px] space-y-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>

    @fluxScripts
    @include('partials.sweetalert')
</body>

</html>