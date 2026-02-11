<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $qrToken;

    public function mount()
    {
        $this->qrToken = env('WFO_QR_TOKEN');
    }
}; ?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Scan untuk Absensi WFO</h1>
        
        <div class="bg-white p-4 inline-block rounded-lg">
            <!-- QR Code Generation using API (simple & works without extra libs) -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrToken) }}" 
                 alt="WFO QR Code" 
                 class="w-64 h-64" />
        </div>

        <p class="mt-6 text-sm text-gray-500 dark:text-gray-400 font-mono break-all max-w-md mx-auto">
            Token: {{ substr($qrToken, 0, 10) }}...
        </p>

        <div class="mt-8">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
