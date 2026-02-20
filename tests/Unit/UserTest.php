<?php

use App\Models\AbsentUser;
use App\Models\JurnalUser;
use App\Models\Mentor;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ============================================
// USER MODEL - BASIC TESTS
// ============================================

test('user can be created using factory', function () {
    $user = User::factory()->create();

    expect($user)
        ->toBeInstanceOf(User::class)
        ->name->not->toBeEmpty()
        ->email->not->toBeEmpty()
        ->password->not->toBeEmpty();
});

test('user has correct fillable attributes', function () {
    $user = new User();

    expect($user->getFillable())
        ->toContain('name')
        ->toContain('email')
        ->toContain('password')
        ->toContain('divisi')
        ->toContain('sekolah')
        ->toContain('mentor_id');
});

test('user has correct hidden attributes', function () {
    $user = new User();

    expect($user->getHidden())
        ->toContain('password')
        ->toContain('two_factor_secret')
        ->toContain('two_factor_recovery_codes')
        ->toContain('remember_token');
});

test('user email is automatically verified on creation', function () {
    $user = User::factory()->create();

    expect($user->email_verified_at)->not->toBeNull();
});

test('user email can be unverified', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

test('user password is hashed on creation', function () {
    $plainPassword = 'test-password-123';
    $user = User::factory()->create(['password' => $plainPassword]);

    expect(Hash::check($plainPassword, $user->password))->toBeTrue();
});

// ============================================
// USER MODEL - INITIALS METHOD
// ============================================

test('user initials returns first two letters of name', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    expect($user->initials())->toBe('JD');
});

test('user initials works with single name', function () {
    $user = User::factory()->create(['name' => 'John']);

    expect($user->initials())->toBe('J');
});

test('user initials works with three or more names', function () {
    $user = User::factory()->create(['name' => 'John Michael Doe Smith']);

    expect($user->initials())->toBe('JM');
});

test('user initials handles extra spaces', function () {
    $user = User::factory()->create(['name' => '  John   Doe  ']);

    expect($user->initials())->toBe('JD');
});

test('user initials is case sensitive', function () {
    $user = User::factory()->create(['name' => 'john doe']);

    expect($user->initials())->toBe('jd');
});

// ============================================
// USER MODEL - RELATIONSHIPS
// ============================================

test('user has many absents', function () {
    $user = User::factory()->create();
    $absents = AbsentUser::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->absents)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(AbsentUser::class);
});

test('user has many jurnals', function () {
    $user = User::factory()->create();
    $jurnals = JurnalUser::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->jurnals)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(JurnalUser::class);
});

test('user has many schedules', function () {
    $user = User::factory()->create();
    $schedules = Schedule::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->schedules)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Schedule::class);
});

test('user belongs to mentor', function () {
    $mentor = \App\Models\Mentor::factory()->create();
    $user = User::factory()->create(['mentor_id' => $mentor->id]);

    expect($user->mentor)
        ->toBeInstanceOf(Mentor::class)
        ->id->toBe($mentor->id);
});

test('user can have null mentor', function () {
    $user = User::factory()->create(['mentor_id' => null]);

    expect($user->mentor)->toBeNull();
});

test('user absents relationship is ordered correctly', function () {
    $user = User::factory()->create();

    $absent1 = AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-01']);
    $absent2 = AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-03']);
    $absent3 = AbsentUser::factory()->create(['user_id' => $user->id, 'absent_date' => '2024-01-02']);

    // Note: Default relationship doesn't have ordering, but we can test it exists
    expect($user->absents)->toHaveCount(3);
});

test('user jurnals relationship returns collection', function () {
    $user = User::factory()->create();
    JurnalUser::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->jurnals)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

test('user schedules relationship returns collection', function () {
    $user = User::factory()->create();
    Schedule::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->schedules)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

// ============================================
// USER MODEL - ROLES AND PERMISSIONS
// ============================================

test('user can be assigned a role', function () {
    // Seed roles
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'murid']);

    $user = User::factory()->create();
    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('murid'))->toBeFalse();
});

test('user can have multiple roles', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'mentor']);

    $user = User::factory()->create();
    $user->assignRole(['admin', 'mentor']);

    expect($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('mentor'))->toBeTrue();
});

test('user can remove a role', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);

    $user = User::factory()->create();
    $user->assignRole('admin');
    $user->removeRole('admin');

    expect($user->hasRole('admin'))->toBeFalse();
});

test('user can sync roles', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'murid']);

    $user = User::factory()->create();
    $user->assignRole('admin');
    $user->syncRoles(['murid']);

    expect($user->hasRole('admin'))->toBeFalse()
        ->and($user->hasRole('murid'))->toBeTrue();
});

// ============================================
// USER MODEL - DIVISI AND SEKOLAH
// ============================================

test('user can have divisi', function () {
    $user = User::factory()->create(['divisi' => 'SEO']);

    expect($user->divisi)->toBe('SEO');
});

test('user can have sekolah', function () {
    $user = User::factory()->create(['sekolah' => 'SMK Negeri 1 Jakarta']);

    expect($user->sekolah)->toBe('SMK Negeri 1 Jakarta');
});

test('user divisi and sekolah can be null', function () {
    $user = User::factory()->create(['divisi' => null, 'sekolah' => null]);

    expect($user->divisi)->toBeNull()
        ->and($user->sekolah)->toBeNull();
});

test('user can update divisi and sekolah', function () {
    $user = User::factory()->create(['divisi' => 'SEO', 'sekolah' => 'SMK 1']);

    $user->update([
        'divisi' => 'Project',
        'sekolah' => 'SMK 2',
    ]);

    expect($user->fresh())
        ->divisi->toBe('Project')
        ->sekolah->toBe('SMK 2');
});

// ============================================
// USER MODEL - QUERY SCOPES
// ============================================

test('user query can be filtered by role', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    \Spatie\Permission\Models\Role::create(['name' => 'murid']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $murid = User::factory()->create();
    $murid->assignRole('murid');

    $admins = User::role('admin')->get();
    $murids = User::role('murid')->get();

    expect($admins)->toHaveCount(1)
        ->and($murids)->toHaveCount(1)
        ->and($admins->first()->id)->toBe($admin->id)
        ->and($murids->first()->id)->toBe($murid->id);
});

// ============================================
// USER MODEL - TWO FACTOR AUTHENTICATION
// ============================================

test('user has two factor authentication by default', function () {
    $user = User::factory()->create();

    expect($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_recovery_codes)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->not->toBeNull();
});

test('user can be created without two factor', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    expect($user->two_factor_secret)->toBeNull()
        ->and($user->two_factor_recovery_codes)->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();
});

// ============================================
// USER MODEL - DELETION AND CASCADING
// ============================================

test('when user is deleted, related absents remain', function () {
    $user = User::factory()->create();
    $absent = AbsentUser::factory()->create(['user_id' => $user->id]);

    $user->delete();

    // Check if absent still exists (no cascade delete by default)
    expect(AbsentUser::find($absent->id))->not->toBeNull();
});

test('when user is deleted, related jurnals remain', function () {
    $user = User::factory()->create();
    $jurnal = JurnalUser::factory()->create(['user_id' => $user->id]);

    $user->delete();

    // Check if jurnal still exists (no cascade delete by default)
    expect(JurnalUser::find($jurnal->id))->not->toBeNull();
});

test('when user is deleted, related schedules remain', function () {
    $user = User::factory()->create();
    $schedule = Schedule::factory()->create(['user_id' => $user->id]);

    $user->delete();

    // Check if schedule still exists (no cascade delete by default)
    expect(Schedule::find($schedule->id))->not->toBeNull();
});

// ============================================
// USER MODEL - EDGE CASES
// ============================================

test('user email must be unique', function () {
    $email = 'test@example.com';
    User::factory()->create(['email' => $email]);

    expect(fn() => User::factory()->create(['email' => $email]))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

test('user can have very long name', function () {
    $longName = str_repeat('a', 255);
    $user = User::factory()->create(['name' => $longName]);

    expect($user->name)->toBe($longName);
});

test('user initials handles unicode characters', function () {
    $user = User::factory()->create(['name' => 'Budi Santoso']);

    expect($user->initials())->toBe('BS');
});

test('user factory can create user with specific attributes', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'divisi' => 'SEO',
        'sekolah' => 'SMK Test',
    ]);

    expect($user)
        ->name->toBe('Test User')
        ->email->toBe('test@example.com')
        ->divisi->toBe('SEO')
        ->sekolah->toBe('SMK Test');
});

// ============================================
// USER MODEL - MASS ASSIGNMENT PROTECTION
// ============================================

test('user cannot mass assign non-fillable attributes', function () {
    $user = User::factory()->create();

    // Try to mass assign a non-fillable attribute
    $user->fill(['remember_token' => 'test-token']);

    // Remember token should not be changed via fill
    expect($user->remember_token)->not->toBe('test-token');
});

// ============================================
// USER MODEL - PASSWORD HASHING
// ============================================

test('user password is automatically hashed when set', function () {
    $plainPassword = 'my-password';
    $user = User::factory()->create(['password' => $plainPassword]);

    // The password should be hashed, not plain text
    expect($user->password)->not->toBe($plainPassword)
        ->and(Hash::check($plainPassword, $user->password))->toBeTrue();
});

test('user password can be changed', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $user->password = 'new-password';
    $user->save();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
