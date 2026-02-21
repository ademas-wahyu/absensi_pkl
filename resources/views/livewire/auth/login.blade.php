<x-layouts.auth>
    <div class="flex flex-col gap-6">
        {{-- Header: Welcome back --}}
        <div class="flex w-full flex-col text-center">
            <h1 class="text-2xl font-bold text-gray-800">Welcome back !</h1>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div>
                <input name="email" value="{{ old('email') }}" type="email" required autofocus autocomplete="email"
                    placeholder="Email" class="login-input-field" />
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative" x-data="{ show: false }">
                <input name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password"
                    placeholder="Password" class="login-input-field pr-10" />
                <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    {{-- Eye icon (password hidden) --}}
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{-- Eye-off icon (password visible) --}}
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-purple-600 hover:text-purple-700 font-medium transition-colors" wire:navigate>
                        Forget password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="pt-2">
                <button type="submit" class="login-btn-gradient w-full" data-test="login-button">
                    Login
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center text-gray-500">
                <span>New user?</span>
                <a href="{{ route('register') }}"
                    class="text-purple-600 hover:text-purple-700 font-semibold underline transition-colors"
                    wire:navigate>Sign Up</a>
            </div>
        @endif
    </div>
</x-layouts.auth>