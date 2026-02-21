<?php

use App\Models\Setting;
use Livewire\Volt\Component;

new class extends Component {
    public string $qrToken;
    public int $secondsRemaining;

    public function mount()
    {
        $this->refreshToken();
    }

    public function refreshToken()
    {
        $this->qrToken = Setting::generateTotpToken();
        $this->secondsRemaining = Setting::getTotpSecondsRemaining();
    }
}; ?>

<div wire:poll.3600s="refreshToken"
    class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-2 text-gray-800 dark:text-gray-200">Scan untuk Absensi WFO</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">QR Code ini berlaku untuk hari ini saja</p>

        <div class="bg-white p-4 inline-block rounded-lg">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrToken) }}"
                alt="WFO QR Code" class="w-64 h-64" />
        </div>

        {{-- Daily validity info --}}
        <div class="mt-4">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                    <line x1="16" x2="16" y1="2" y2="6" />
                    <line x1="8" x2="8" y1="2" y2="6" />
                    <line x1="3" x2="21" y1="10" y2="10" />
                </svg>
                <span class="font-medium">Berlaku: {{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <p class="text-xs text-gray-400 mt-2">QR Code akan otomatis berganti setiap hari</p>
        </div>

        <div class="mt-8">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>