<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('absent_users', 'absent_users')
    ->middleware(['auth', 'verified'])
    ->name('absent_users');

Route::view('jurnal_users', 'jurnal_users')
    ->middleware(['auth', 'verified'])
    ->name('jurnal_users');

    Route::view('divisi_users', 'divisi_users')
    ->middleware(['auth', 'verified'])
    ->name('divisi_users');

    Route::view('dashboard_admin', 'dashboard_admin')
    ->middleware(['auth', 'verified'])
    ->name('dashboard_admin');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
