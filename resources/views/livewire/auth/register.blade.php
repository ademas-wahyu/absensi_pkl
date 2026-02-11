<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input name="name" :label="__('Name')" :value="old('name')" type="text" required autofocus
                autocomplete="name" :placeholder="__('Full name')" />

            <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required
                autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
                :placeholder="__('Password')" viewable />

            <!-- Confirm Password -->
            <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" required
                autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

            <!-- Divisi -->
            <flux:select name="divisi" :label="__('Divisi')" required :placeholder="__('Pilih Divisi')">
                @foreach ($divisis as $divisi)
                    <flux:select.option value="{{ $divisi->nama_divisi }}">{{ $divisi->nama_divisi }}</flux:select.option>
                @endforeach
            </flux:select>
            @error('divisi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <!-- Sekolah -->
            <flux:input name="sekolah" :label="__('Sekolah')" :value="old('sekolah')" type="text" required
                autocomplete="organization" :placeholder="__('Nama Sekolah')" />

            <!-- Mentor (Optional) -->
            <flux:select name="mentor_id" :label="__('Mentor (Optional)')" :placeholder="__('Pilih Mentor (Jika ada)')">
                <flux:select.option value="">{{ __('Tidak ada / Belum tahu') }}</flux:select.option>
                @foreach ($mentors as $mentor)
                    <flux:select.option value="{{ $mentor->id }}">{{ $mentor->nama_mentor }}</flux:select.option>
                @endforeach
            </flux:select>
            @error('mentor_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="pt-2">
                <button type="submit" class="login-btn-gradient w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </button>
            </div>
        </form>

        <div class="space-x-1 text-center text-sm text-gray-500">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}"
                class="text-purple-600 hover:text-purple-700 font-semibold underline transition-colors"
                wire:navigate>{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts.auth>