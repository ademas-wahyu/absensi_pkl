<?php

use App\Livewire\AbsentUserInput;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Login as a user (create one if needed or pick first)
$user = User::first();
if (!$user) {
    echo "No user found to test with.\n";
    exit(1);
}
Auth::login($user);

$component = new AbsentUserInput();
$token = env('WFO_QR_TOKEN');

echo "Testing with token: " . $token . "\n";

// Simulate verifyQrCode
// Note: We can't easily assert the alert() JS call in this script without Livewire test helpers,
// but we can check if it returns early or throws error.
// Actually, verifyQrCode returns void. It dispatches browser events.

try {
    $component->verifyQrCode($token);
    echo "Verification executed without exception.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
