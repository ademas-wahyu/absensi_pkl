<?php

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================
// SCHEDULE MODEL - BASIC TESTS
// ============================================

test('schedule can be created using factory', function () {
    $schedule = Schedule::factory()->create();

    expect($schedule)
        ->toBeInstanceOf(Schedule::class)
        ->user_id->not->toBeNull()
        ->date->not->toBeNull()
        ->type->not->toBeNull();
});

test('schedule has correct fillable attributes', function () {
    $schedule = new Schedule();

    expect($schedule->getFillable())
        ->toContain('user_id')
        ->toContain('date')
        ->toContain('type')
        ->toContain('notes')
        ->toContain('created_by');
});

test('schedule date is cast to carbon date', function () {
    $schedule = Schedule::factory()->create(['date' => '2024-01-15']);

    expect($schedule->date)
        ->toBeInstanceOf(\Carbon\Carbon::class)
        ->format('Y-m-d')->toBe('2024-01-15');
});

test('schedule can have notes', function () {
    $schedule = Schedule::factory()->create(['notes' => 'Test notes']);

    expect($schedule->notes)->toBe('Test notes');
});

test('schedule notes can be null', function () {
    $schedule = Schedule::factory()->create(['notes' => null]);

    expect($schedule->notes)->toBeNull();
});

// ============================================
// SCHEDULE MODEL - TYPE METHODS
// ============================================

test('schedule getTypeLabel returns correct label for wfh', function () {
    $schedule = Schedule::factory()->create(['type' => 'wfh']);

    expect($schedule->getTypeLabel())->toBe('Work From Home');
});

test('schedule getTypeLabel returns correct label for wfo', function () {
    $schedule = Schedule::factory()->create(['type' => 'wfo']);

    expect($schedule->getTypeLabel())->toBe('Work From Office');
});

test('schedule getTypeLabel returns correct label for libur', function () {
    $schedule = Schedule::factory()->create(['type' => 'libur']);

    expect($schedule->getTypeLabel())->toBe('Libur');
});

test('schedule getTypeLabel returns type itself for unknown type', function () {
    $schedule = Schedule::factory()->create(['type' => 'custom']);

    expect($schedule->getTypeLabel())->toBe('custom');
});

test('schedule getTypeColor returns correct color for wfh', function () {
    $schedule = Schedule::factory()->create(['type' => 'wfh']);

    expect($schedule->getTypeColor())->toBe('blue');
});

test('schedule getTypeColor returns correct color for wfo', function () {
    $schedule = Schedule::factory()->create(['type' => 'wfo']);

    expect($schedule->getTypeColor())->toBe('green');
});

test('schedule getTypeColor returns correct color for libur', function () {
    $schedule = Schedule::factory()->create(['type' => 'libur']);

    expect($schedule->getTypeColor())->toBe('red');
});

test('schedule getTypeColor returns zinc for unknown type', function () {
    $schedule = Schedule::factory()->create(['type' => 'custom']);

    expect($schedule->getTypeColor())->toBe('zinc');
});

// ============================================
// SCHEDULE MODEL - RELATIONSHIPS
// ============================================

test('schedule belongs to user', function () {
    $user = User::factory()->create();
    $schedule = Schedule::factory()->create(['user_id' => $user->id]);

    expect($schedule->user)
        ->toBeInstanceOf(User::class)
        ->id->toBe($user->id);
});

test('schedule belongs to creator', function () {
    $creator = User::factory()->create();
    $schedule = Schedule::factory()->create(['created_by' => $creator->id]);

    expect($schedule->creator)
        ->toBeInstanceOf(User::class)
        ->id->toBe($creator->id);
});

test('schedule user and creator can be different', function () {
    $user = User::factory()->create();
    $creator = User::factory()->create();

    $schedule = Schedule::factory()->create([
        'user_id' => $user->id,
        'created_by' => $creator->id,
    ]);

    expect($schedule->user->id)->toBe($user->id)
        ->and($schedule->creator->id)->toBe($creator->id);
});

test('schedule user and creator can be same', function () {
    $user = User::factory()->create();

    $schedule = Schedule::factory()->create([
        'user_id' => $user->id,
        'created_by' => $user->id,
    ]);

    expect($schedule->user->id)->toBe($user->id)
        ->and($schedule->creator->id)->toBe($user->id);
});

// ============================================
// SCHEDULE MODEL - SCOPE: FOR USER
// ============================================

test('schedule scope for user filters by user id', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $schedule1 = Schedule::factory()->create(['user_id' => $user1->id]);
    $schedule2 = Schedule::factory()->create(['user_id' => $user1->id]);
    $schedule3 = Schedule::factory()->create(['user_id' => $user2->id]);

    $user1Schedules = Schedule::forUser($user1->id)->get();
    $user2Schedules = Schedule::forUser($user2->id)->get();

    expect($user1Schedules)
        ->toHaveCount(2)
        ->each->user_id->toBe($user1->id);

    expect($user2Schedules)
        ->toHaveCount(1)
        ->first()->user_id->toBe($user2->id);
});

test('schedule scope for user returns empty when no schedules', function () {
    $user = User::factory()->create();

    $schedules = Schedule::forUser($user->id)->get();

    expect($schedules)->toHaveCount(0);
});

// ============================================
// SCHEDULE MODEL - SCOPE: DATE RANGE
// ============================================

test('schedule scope date range filters correctly', function () {
    $schedule1 = Schedule::factory()->create(['date' => '2024-01-10']);
    $schedule2 = Schedule::factory()->create(['date' => '2024-01-15']);
    $schedule3 = Schedule::factory()->create(['date' => '2024-01-20']);
    $schedule4 = Schedule::factory()->create(['date' => '2024-01-25']);

    $schedules = Schedule::dateRange('2024-01-12', '2024-01-22')->get();

    expect($schedules)
        ->toHaveCount(2)
        ->pluck('date')->each->toBeBetween(
            \Carbon\Carbon::parse('2024-01-12'),
            \Carbon\Carbon::parse('2024-01-22')
        );
});

test('schedule scope date range includes boundary dates', function () {
    $schedule1 = Schedule::factory()->create(['date' => '2024-01-10']);
    $schedule2 = Schedule::factory()->create(['date' => '2024-01-15']);
    $schedule3 = Schedule::factory()->create(['date' => '2024-01-20']);

    $schedules = Schedule::dateRange('2024-01-10', '2024-01-20')->get();

    expect($schedules)->toHaveCount(3);
});

test('schedule scope date range returns empty when no schedules in range', function () {
    Schedule::factory()->create(['date' => '2024-01-10']);
    Schedule::factory()->create(['date' => '2024-01-15']);

    $schedules = Schedule::dateRange('2024-02-01', '2024-02-28')->get();

    expect($schedules)->toHaveCount(0);
});

test('schedule scope date range works with carbon dates', function () {
    Schedule::factory()->create(['date' => '2024-01-15']);

    $startDate = \Carbon\Carbon::parse('2024-01-10');
    $endDate = \Carbon\Carbon::parse('2024-01-20');

    $schedules = Schedule::dateRange($startDate, $endDate)->get();

    expect($schedules)->toHaveCount(1);
});

// ============================================
// SCHEDULE MODEL - SCOPE: OF TYPE
// ============================================

test('schedule scope of type filters by wfh', function () {
    $schedule1 = Schedule::factory()->create(['type' => 'wfh']);
    $schedule2 = Schedule::factory()->create(['type' => 'wfo']);
    $schedule3 = Schedule::factory()->create(['type' => 'wfh']);

    $wfhSchedules = Schedule::ofType('wfh')->get();

    expect($wfhSchedules)
        ->toHaveCount(2)
        ->each->type->toBe('wfh');
});

test('schedule scope of type filters by wfo', function () {
    Schedule::factory()->count(3)->create(['type' => 'wfh']);
    Schedule::factory()->count(2)->create(['type' => 'wfo']);

    $wfoSchedules = Schedule::ofType('wfo')->get();

    expect($wfoSchedules)
        ->toHaveCount(2)
        ->each->type->toBe('wfo');
});

test('schedule scope of type filters by libur', function () {
    Schedule::factory()->count(3)->create(['type' => 'wfh']);
    Schedule::factory()->count(2)->create(['type' => 'libur']);

    $liburSchedules = Schedule::ofType('libur')->get();

    expect($liburSchedules)
        ->toHaveCount(2)
        ->each->type->toBe('libur');
});

test('schedule scope of type returns empty for non-existent type', function () {
    Schedule::factory()->count(3)->create(['type' => 'wfh']);

    $customSchedules = Schedule::ofType('custom')->get();

    expect($customSchedules)->toHaveCount(0);
});

// ============================================
// SCHEDULE MODEL - COMBINED SCOPES
// ============================================

test('schedule scopes can be combined', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // User 1 schedules
    Schedule::factory()->create(['user_id' => $user1->id, 'date' => '2024-01-15', 'type' => 'wfh']);
    Schedule::factory()->create(['user_id' => $user1->id, 'date' => '2024-01-16', 'type' => 'wfo']);
    Schedule::factory()->create(['user_id' => $user1->id, 'date' => '2024-01-20', 'type' => 'wfh']);

    // User 2 schedules
    Schedule::factory()->create(['user_id' => $user2->id, 'date' => '2024-01-15', 'type' => 'wfh']);

    $schedules = Schedule::forUser($user1->id)
        ->dateRange('2024-01-14', '2024-01-17')
        ->ofType('wfh')
        ->get();

    expect($schedules)
        ->toHaveCount(1)
        ->first()->user_id->toBe($user1->id)
        ->first()->type->toBe('wfh')
        ->first()->date->format('Y-m-d')->toBe('2024-01-15');
});

test('schedule scopes with multiple filters', function () {
    $user = User::factory()->create();

    Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-10', 'type' => 'wfh']);
    Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-15', 'type' => 'wfh']);
    Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-20', 'type' => 'wfo']);

    $schedules = Schedule::forUser($user->id)
        ->dateRange('2024-01-12', '2024-01-18')
        ->ofType('wfh')
        ->get();

    expect($schedules)
        ->toHaveCount(1)
        ->first()->date->format('Y-m-d')->toBe('2024-01-15');
});

// ============================================
// SCHEDULE MODEL - FACTORY STATES
// ============================================

test('schedule factory wfh state creates wfh schedule', function () {
    $schedule = Schedule::factory()->wfh()->create();

    expect($schedule->type)->toBe('wfh');
});

test('schedule factory wfo state creates wfo schedule', function () {
    $schedule = Schedule::factory()->wfo()->create();

    expect($schedule->type)->toBe('wfo');
});

test('schedule factory libur state creates libur schedule', function () {
    $schedule = Schedule::factory()->libur()->create();

    expect($schedule->type)->toBe('libur');
});

test('schedule factory today state creates schedule for today', function () {
    $schedule = Schedule::factory()->today()->create();

    expect($schedule->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
});

test('schedule factory tomorrow state creates schedule for tomorrow', function () {
    $schedule = Schedule::factory()->tomorrow()->create();

    expect($schedule->date->format('Y-m-d'))->toBe(now()->addDay()->format('Y-m-d'));
});

test('schedule factory forDate state creates schedule for specific date', function () {
    $schedule = Schedule::factory()->forDate('2024-12-25')->create();

    expect($schedule->date->format('Y-m-d'))->toBe('2024-12-25');
});

test('schedule factory withNotes state creates schedule with notes', function () {
    $notes = 'Important schedule notes';
    $schedule = Schedule::factory()->withNotes($notes)->create();

    expect($schedule->notes)->toBe($notes);
});

test('schedule factory createdBy state creates schedule with creator', function () {
    $admin = User::factory()->create();
    $schedule = Schedule::factory()->createdBy($admin)->create();

    expect($schedule->created_by)->toBe($admin->id);
});

// ============================================
// SCHEDULE MODEL - CRUD OPERATIONS
// ============================================

test('schedule can be updated', function () {
    $schedule = Schedule::factory()->create(['type' => 'wfh']);

    $schedule->update(['type' => 'wfo']);

    expect($schedule->fresh()->type)->toBe('wfo');
});

test('schedule can be deleted', function () {
    $schedule = Schedule::factory()->create();

    $scheduleId = $schedule->id;
    $schedule->delete();

    expect(Schedule::find($scheduleId))->toBeNull();
});

test('multiple schedules can be created for same user', function () {
    $user = User::factory()->create();

    $schedule1 = Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-10']);
    $schedule2 = Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-11']);
    $schedule3 = Schedule::factory()->create(['user_id' => $user->id, 'date' => '2024-01-12']);

    expect($user->schedules)->toHaveCount(3);
});

// ============================================
// SCHEDULE MODEL - EDGE CASES
// ============================================

test('schedule can have same date for different users', function () {
    $date = '2024-01-15';
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $schedule1 = Schedule::factory()->create(['user_id' => $user1->id, 'date' => $date]);
    $schedule2 = Schedule::factory()->create(['user_id' => $user2->id, 'date' => $date]);

    expect($schedule1->date->format('Y-m-d'))->toBe($date)
        ->and($schedule2->date->format('Y-m-d'))->toBe($date);
});

test('schedule can have long notes', function () {
    $longNotes = str_repeat('Long note content. ', 100);
    $schedule = Schedule::factory()->create(['notes' => $longNotes]);

    expect($schedule->notes)->toBe($longNotes);
});

test('schedule type is case sensitive', function () {
    $schedule = Schedule::factory()->create(['type' => 'WFH']);

    // getTypeLabel should return the type itself for unknown type
    expect($schedule->getTypeLabel())->toBe('WFH');
});

// ============================================
// SCHEDULE MODEL - DATE HANDLING
// ============================================

test('schedule date can be in the past', function () {
    $pastDate = now()->subMonth()->format('Y-m-d');
    $schedule = Schedule::factory()->create(['date' => $pastDate]);

    expect($schedule->date->format('Y-m-d'))->toBe($pastDate);
});

test('schedule date can be in the future', function () {
    $futureDate = now()->addMonth()->format('Y-m-d');
    $schedule = Schedule::factory()->create(['date' => $futureDate]);

    expect($schedule->date->format('Y-m-d'))->toBe($futureDate);
});

test('schedule date can be manipulated with carbon', function () {
    $schedule = Schedule::factory()->create(['date' => '2024-01-15']);

    expect($schedule->date)
        ->toBeInstanceOf(\Carbon\Carbon::class)
        ->day->toBe(15)
        ->month->toBe(1)
        ->year->toBe(2024);
});

// ============================================
// SCHEDULE MODEL - QUERY OPTIMIZATION
// ============================================

test('schedule query can eager load relationships', function () {
    $user = User::factory()->create();
    $creator = User::factory()->create();

    Schedule::factory()->create([
        'user_id' => $user->id,
        'created_by' => $creator->id,
    ]);

    $schedule = Schedule::with(['user', 'creator'])->first();

    expect($schedule->relationLoaded('user'))->toBeTrue()
        ->and($schedule->relationLoaded('creator'))->toBeTrue();
});

test('schedule can check if relationship is loaded', function () {
    $schedule = Schedule::factory()->create();

    expect($schedule->relationLoaded('user'))->toBeFalse();

    $schedule->load('user');

    expect($schedule->relationLoaded('user'))->toBeTrue();
});
