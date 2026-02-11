<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard (untuk semua role - admin & murid)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route khusus Admin (daftar anak)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::view('daftar-anak', 'jumlah_anak')->name('jumlah_anak');
});

// Route khusus Admin (setting)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::view('setting', 'setting')->name('setting');
});

// Route untuk kedua role (admin & murid)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('absen', 'absent_users')->name('absent_users');
    Route::view('jurnal', 'jurnal_users')->name('jurnal_users');
    Route::view('divisi_users', 'divisi_users')->name('divisi_users');
});

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

