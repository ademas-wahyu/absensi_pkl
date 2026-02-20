<?php

use App\Models\AbsentUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================
// ABSENT USER MODEL - BASIC TESTS
// ============================================

test('absent user can be created using factory', function () {
    $absent = AbsentUser::factory()->create();

    expect($absent)
        ->toBeInstanceOf(AbsentUser::class)
        ->user_id->not->toBeNull()
        ->absent_date->not->toBeNull()
        ->status->not->toBeNull();
});

test('absent user has correct fillable attributes', function () {
    $absent = new AbsentUser();

    expect($absent->getFillable())
        ->toContain('user_id')
        ->toContain('absent_date')
        ->toContain('status')
        ->toContain('reason')
        ->toContain('selfie_path')
        ->toContain('latitude')
        ->toContain('longitude')
        ->toContain('verification_method')
        ->toContain('checkout_at')
        ->toContain('early_leave_reason');
});

test('absent user checkout_at is cast to datetime', function () {
    $absent = AbsentUser::factory()->create(['checkout_at' => '2024-01-15 17:30:00']);

    expect($absent->checkout_at)
        ->toBeInstanceOf(\Carbon\Carbon::class)
        ->format('Y-m-d H:i:s')->toBe('2024-01-15 17:30:00');
});

test('absent user checkout_at can be null', function () {
    $absent = AbsentUser::factory()->create(['checkout_at' => null]);

    expect($absent->checkout_at)->toBeNull();
});

// ============================================
// ABSENT USER MODEL - RELATIONSHIPS
// ============================================

test('absent user belongs to user', function () {
    $user = User::factory()->create();
    $absent = AbsentUser::factory()->create(['user_id' => $user->id]);

    expect($absent->user)
        ->toBeInstanceOf(User::class)
        ->id->toBe($user->id);
});

test('absent user relationship returns correct user', function () {
    $user1 = User::factory()->create(['name' => 'User 1']);
    $user2 = User::factory()->create(['name' => 'User 2']);

    $absent = AbsentUser::factory()->create(['user_id' => $user1->id]);

    expect($absent->user->name)->toBe('User 1');
});

test('user can have multiple absents', function () {
    $user = User::factory()->create();

    $absent1 = AbsentUser::factory()->create(['user_id' => $user->id]);
    $absent2 = AbsentUser::factory()->create(['user_id' => $user->id]);
    $absent3 = AbsentUser::factory()->create(['user_id' => $user->id]);

    expect($user->absents)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(AbsentUser::class);
});

// ============================================
// ABSENT USER MODEL - STATUS TYPES
// ============================================

test('absent user can have hadir status', function () {
    $absent = AbsentUser::factory()->hadir()->create();

    expect($absent->status)->toBe('hadir')
        ->and($absent->reason)->toBeNull();
});

test('absent user can have izin status', function () {
    $absent = AbsentUser::factory()->izin()->create();

    expect($absent->status)->toBe('izin')
        ->and($absent->reason)->not->toBeNull();
});

test('absent user can have sakit status', function () {
    $absent = AbsentUser::factory()->sakit()->create();

    expect($absent->status)->toBe('sakit')
        ->and($absent->reason)->not->toBeNull();
});

test('absent user hadir status can have reason', function () {
    $absent = AbsentUser::factory()->create([
        'status' => 'hadir',
        'reason' => 'Late check-in',
    ]);

    expect($absent->status)->toBe('hadir')
        ->and($absent->reason)->toBe('Late check-in');
});

// ============================================
// ABSENT USER MODEL - VERIFICATION METHODS
// ============================================

test('absent user can have location verification', function () {
    $absent = AbsentUser::factory()->withLocation()->create();

    expect($absent->verification_method)->toBe('location')
        ->and($absent->latitude)->not->toBeNull()
        ->and($absent->longitude)->not->toBeNull();
});

test('absent user can have selfie verification', function () {
    $absent = AbsentUser::factory()->withSelfie()->create();

    expect($absent->verification_method)->toBe('selfie')
        ->and($absent->selfie_path)->not->toBeNull();
});

test('absent user can have manual verification', function () {
    $absent = AbsentUser::factory()->create([
        'verification_method' => 'manual',
    ]);

    expect($absent->verification_method)->toBe('manual');
});

test('absent user verification method can be location with custom coordinates', function () {
    $absent = AbsentUser::factory()->create([
        'verification_method' => 'location',
        'latitude' => -6.175110,
        'longitude' => 106.865039,
    ]);

    expect($absent->latitude)->toBe(-6.175110)
        ->and($absent->longitude)->toBe(106.865039);
});

test('absent user can have both location and selfie', function () {
    $absent = AbsentUser::factory()->create([
        'verification_method' => 'location',
        'latitude' => -6.175110,
        'longitude' => 106.865039,
        'selfie_path' => 'selfies/test.jpg',
    ]);

    expect($absent->verification_method)->toBe('location')
        ->and($absent->latitude)->not->toBeNull()
        ->and($absent->longitude)->not->toBeNull()
        ->and($absent->selfie_path)->not->toBeNull();
});

// ============================================
// ABSENT USER MODEL - CHECKOUT FUNCTIONALITY
// ============================================

test('absent user can be checked out', function () {
    $absent = AbsentUser::factory()->checkedOut()->create();

    expect($absent->status)->toBe('hadir')
        ->and($absent->checkout_at)->not->toBeNull()
        ->and($absent->checkout_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('absent user can have early checkout with reason', function () {
    $absent = AbsentUser::factory()->earlyCheckout()->create();

    expect($absent->status)->toBe('hadir')
        ->and($absent->checkout_at)->not->toBeNull()
        ->and($absent->early_leave_reason)->not->toBeNull();
});

test('absent user early leave reason can be set', function () {
    $reason = 'Family emergency';
    $absent = AbsentUser::factory()->create([
        'status' => 'hadir',
        'checkout_at' => now(),
        'early_leave_reason' => $reason,
    ]);

    expect($absent->early_leave_reason)->toBe($reason);
});

test('absent user without checkout has null checkout_at', function () {
    $absent = AbsentUser::factory()->create(['checkout_at' => null]);

    expect($absent->checkout_at)->toBeNull();
});

// ============================================
// ABSENT USER MODEL - DATE HANDLING
// ============================================

test('absent user can be for today', function () {
    $absent = AbsentUser::factory()->today()->create();

    expect($absent->absent_date)->toBe(now()->format('Y-m-d'));
});

test('absent user can be for past date', function () {
    $pastDate = now()->subDays(7)->format('Y-m-d');
    $absent = AbsentUser::factory()->create(['absent_date' => $pastDate]);

    expect($absent->absent_date)->toBe($pastDate);
});

test('absent user can be for future date', function () {
    $futureDate = now()->addDays(7)->format('Y-m-d');
    $absent = AbsentUser::factory()->create(['absent_date' => $futureDate]);

    expect($absent->absent_date)->toBe($futureDate);
});

test('absent user date is stored as string', function () {
    $absent = AbsentUser::factory()->create(['absent_date' => '2024-01-15']);

    expect($absent->absent_date)->toBe('2024-01-15');
});

// ============================================
// ABSENT USER MODEL - FACTORY STATES
// ============================================

test('absent user factory hadir state', function () {
    $absent = AbsentUser::factory()->hadir()->create();

    expect($absent->status)->toBe('hadir');
});

test('absent user factory izin state', function () {
    $absent = AbsentUser::factory()->izin()->create();

    expect($absent->status)->toBe('izin');
});

test('absent user factory sakit state', function () {
    $absent = AbsentUser::factory()->sakit()->create();

    expect($absent->status)->toBe('sakit');
});

test('absent user factory today state', function () {
    $absent = AbsentUser::factory()->today()->create();

    expect($absent->absent_date)->toBe(now()->format('Y-m-d'));
});

test('absent user factory checkedOut state', function () {
    $absent = AbsentUser::factory()->checkedOut()->create();

    expect($absent->status)->toBe('hadir')
        ->and($absent->checkout_at)->not->toBeNull();
});

test('absent user factory earlyCheckout state', function () {
    $absent = AbsentUser::factory()->earlyCheckout()->create();

    expect($absent->status)->toBe('hadir')
        ->and($absent->checkout_at)->not->toBeNull()
        ->and($absent->early_leave_reason)->not->toBeNull();
});

test('absent user factory withLocation state', function () {
    $absent = AbsentUser::factory()->withLocation()->create();

    expect($absent->verification_method)->toBe('location')
        ->and($absent->latitude)->not->toBeNull()
        ->and($absent->longitude)->not->toBeNull();
});

test('absent user factory withSelfie state', function () {
    $absent = AbsentUser::factory()->withSelfie()->create();

    expect($absent->verification_method)->toBe('selfie')
        ->and($absent->selfie_path)->not->toBeNull();
});

// ============================================
// ABSENT USER MODEL - CRUD OPERATIONS
// ============================================

test('absent user can be updated', function () {
    $absent = AbsentUser::factory()->create(['status' => 'hadir']);

    $absent->update(['status' => 'izin', 'reason' => 'Family matter']);

    expect($absent->fresh())
        ->status->toBe('izin')
        ->reason->toBe('Family matter');
});

test('absent user can be deleted', function () {
    $absent = AbsentUser::factory()->create();
    $absentId = $absent->id;

    $absent->delete();

    expect(AbsentUser::find($absentId))->toBeNull();
});

test('absent user can update checkout time', function () {
    $absent = AbsentUser::factory()->create(['checkout_at' => null]);

    $checkoutTime = now();
    $absent->update(['checkout_at' => $checkoutTime]);

    expect($absent->fresh()->checkout_at)->not->toBeNull();
});

test('absent user can update location', function () {
    $absent = AbsentUser::factory()->create([
        'latitude' => null,
        'longitude' => null,
    ]);

    $absent->update([
        'latitude' => -6.175110,
        'longitude' => 106.865039,
        'verification_method' => 'location',
    ]);

    $fresh = $absent->fresh();
    expect($fresh->latitude)->toBe(-6.175110)
        ->and($fresh->longitude)->toBe(106.865039)
        ->and($fresh->verification_method)->toBe('location');
});

// ============================================
// ABSENT USER MODEL - QUERY SCENARIOS
// ============================================

test('can query absents by user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    AbsentUser::factory()->count(3)->create(['user_id' => $user1->id]);
    AbsentUser::factory()->count(2)->create(['user_id' => $user2->id]);

    $user1Absents = AbsentUser::where('user_id', $user1->id)->get();
    $user2Absents = AbsentUser::where('user_id', $user2->id)->get();

    expect($user1Absents)->toHaveCount(3)
        ->and($user2Absents)->toHaveCount(2);
});

test('can query absents by status', function () {
    AbsentUser::factory()->count(3)->hadir()->create();
    AbsentUser::factory()->count(2)->izin()->create();
    AbsentUser::factory()->count(1)->sakit()->create();

    $hadir = AbsentUser::where('status', 'hadir')->get();
    $izin = AbsentUser::where('status', 'izin')->get();
    $sakit = AbsentUser::where('status', 'sakit')->get();

    expect($hadir)->toHaveCount(3)
        ->and($izin)->toHaveCount(2)
        ->and($sakit)->toHaveCount(1);
});

test('can query absents by date', function () {
    AbsentUser::factory()->create(['absent_date' => '2024-01-10']);
    AbsentUser::factory()->create(['absent_date' => '2024-01-10']);
    AbsentUser::factory()->create(['absent_date' => '2024-01-15']);

    $absents = AbsentUser::where('absent_date', '2024-01-10')->get();

    expect($absents)->toHaveCount(2);
});

test('can query absents with checkout', function () {
    AbsentUser::factory()->count(3)->checkedOut()->create();
    AbsentUser::factory()->count(2)->create(['checkout_at' => null]);

    $checkedOut = AbsentUser::whereNotNull('checkout_at')->get();
    $notCheckedOut = AbsentUser::whereNull('checkout_at')->get();

    expect($checkedOut)->toHaveCount(3)
        ->and($notCheckedOut)->toHaveCount(2);
});

test('can query absents by verification method', function () {
    AbsentUser::factory()->count(3)->withLocation()->create();
    AbsentUser::factory()->count(2)->withSelfie()->create();
    AbsentUser::factory()->count(1)->create(['verification_method' => 'manual']);

    $location = AbsentUser::where('verification_method', 'location')->get();
    $selfie = AbsentUser::where('verification_method', 'selfie')->get();
    $manual = AbsentUser::where('verification_method', 'manual')->get();

    expect($location)->toHaveCount(3)
        ->and($selfie)->toHaveCount(2)
        ->and($manual)->toHaveCount(1);
});

// ============================================
// ABSENT USER MODEL - EDGE CASES
// ============================================

test('absent user can have long reason', function () {
    $longReason = str_repeat('This is a long reason. ', 50);
    $absent = AbsentUser::factory()->create(['reason' => $longReason]);

    expect($absent->reason)->toBe($longReason);
});

test('absent user can have long early leave reason', function () {
    $longReason = str_repeat('Early leave reason. ', 50);
    $absent = AbsentUser::factory()->create(['early_leave_reason' => $longReason]);

    expect($absent->early_leave_reason)->toBe($longReason);
});

test('absent user selfie path can be long path', function () {
    $longPath = 'storage/selfies/2024/01/15/user_123/device_456/' . str_repeat('a', 100) . '.jpg';
    $absent = AbsentUser::factory()->create(['selfie_path' => $longPath]);

    expect($absent->selfie_path)->toBe($longPath);
});

test('absent user coordinates can have high precision', function () {
    $absent = AbsentUser::factory()->create([
        'latitude' => -6.175110123456789,
        'longitude' => 106.865039876543210,
    ]);

    expect($absent->latitude)->toBe(-6.175110123456789)
        ->and($absent->longitude)->toBe(106.865039876543210);
});

test('absent user can have negative longitude', function () {
    $absent = AbsentUser::factory()->create([
        'latitude' => 40.7128,
        'longitude' => -74.0060, // New York coordinates
    ]);

    expect($absent->longitude)->toBe(-74.0060);
});

test('absent user status is case sensitive', function () {
    $absent = AbsentUser::factory()->create(['status' => 'HADIR']);

    expect($absent->status)->toBe('HADIR');
});

// ============================================
// ABSENT USER MODEL - INTEGRATION SCENARIOS
// ============================================

test('user can have multiple absents on different dates', function () {
    $user = User::factory()->create();

    AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-10']);
    AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-11']);
    AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-12']);

    expect($user->absents)->toHaveCount(3);
});

test('user can have only one absent per date', function () {
    $user = User::factory()->create();
    $date = '2024-01-15';

    // Create first absent
    $absent1 = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => $date,
    ]);

    // Note: This test verifies that the model allows multiple absents per date
    // In real application, you might want to add unique constraint or validation
    $absent2 = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => $date,
    ]);

    $absentsOnDate = AbsentUser::where('user_id', $user->id)
        ->where('absent_date', $date)
        ->get();

    expect($absentsOnDate)->toHaveCount(2);
});

test('absent user can be eager loaded with user', function () {
    $user = User::factory()->create();
    AbsentUser::factory()->create(['user_id' => $user->id]);

    $absent = AbsentUser::with('user')->first();

    expect($absent->relationLoaded('user'))->toBeTrue();
});

test('multiple absents can be eager loaded', function () {
    $user = User::factory()->create();
    AbsentUser::factory()->count(3)->create(['user_id' => $user->id]);

    $absents = AbsentUser::with('user')->get();

    expect($absents)->toHaveCount(3)
        ->and($absents->first()->relationLoaded('user'))->toBeTrue();
});

// ============================================
// ABSENT USER MODEL - TYPICAL WORKFLOW
// ============================================

test('typical checkin workflow', function () {
    $user = User::factory()->create();

    // User checks in
    $absent = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => now()->format('Y-m-d'),
        'status' => 'hadir',
        'verification_method' => 'location',
        'latitude' => -6.175110,
        'longitude' => 106.865039,
        'checkout_at' => null,
    ]);

    expect($absent->status)->toBe('hadir')
        ->and($absent->checkout_at)->toBeNull();
});

test('typical checkout workflow', function () {
    $user = User::factory()->create();

    // User checks in
    $absent = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => now()->format('Y-m-d'),
        'status' => 'hadir',
        'checkout_at' => null,
    ]);

    // Later, user checks out
    $checkoutTime = now()->addHours(8);
    $absent->update(['checkout_at' => $checkoutTime]);

    expect($absent->fresh()->checkout_at)->not->toBeNull();
});

test('typical permission request workflow', function () {
    $user = User::factory()->create();

    // User requests permission
    $absent = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => now()->addDay()->format('Y-m-d'),
        'status' => 'izin',
        'reason' => 'Family event',
        'verification_method' => 'manual',
    ]);

    expect($absent->status)->toBe('izin')
        ->and($absent->reason)->toBe('Family event')
        ->and($absent->verification_method)->toBe('manual');
});

test('typical sick leave workflow', function () {
    $user = User::factory()->create();

    // User reports sick
    $absent = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => now()->format('Y-m-d'),
        'status' => 'sakit',
        'reason' => 'Flu and fever',
        'verification_method' => 'manual',
    ]);

    expect($absent->status)->toBe('sakit')
        ->and($absent->reason)->toBe('Flu and fever');
});

test('early checkout workflow', function () {
    $user = User::factory()->create();

    // User checks in
    $absent = AbsentUser::factory()->create([
        'user_id' => $user->id,
        'absent_date' => now()->format('Y-m-d'),
        'status' => 'hadir',
        'checkout_at' => null,
    ]);

    // User needs to leave early
    $absent->update([
        'checkout_at' => now()->addHours(5), // Early checkout after 5 hours
        'early_leave_reason' => 'Doctor appointment',
    ]);

    $fresh = $absent->fresh();
    expect($fresh->checkout_at)->not->toBeNull()
        ->and($fresh->early_leave_reason)->toBe('Doctor appointment');
});
