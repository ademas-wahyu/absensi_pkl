<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf


            <flux:input name="email" :label="__('Email Address')" type="email" required autofocus
                placeholder="email@example.com" />

            <div class="pt-2">
                <button type="submit" class="login-btn-gradient w-full" data-test="email-password-reset-link-button">
                    {{ __('Email password reset link') }}
                </button>
            </div>
        </form>

        <div class="space-x-1 text-center text-sm text-gray-500">
            <span>{{ __('Or, return to') }}</span>
            <a href="{{ route('login') }}"
                class="text-purple-600 hover:text-purple-700 font-semibold underline transition-colors"
                wire:navigate>{{ __('log in') }}</a>
        </div>
    </div>
</x-layouts.auth>