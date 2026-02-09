<?php

namespace Tests\Feature;

use App\Models\JurnalUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Authorization Security Tests
 * 
 * This test suite verifies that authorization checks are properly implemented
 * to prevent privilege escalation and unauthorized access to admin functions.
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    /**
     * Test that regular users cannot access admin user management functions
     */
    public function test_regular_user_cannot_create_user_via_data_admin(): void
    {
        // Create a regular user (murid)
        $regularUser = User::factory()->create();
        $regularUser->assignRole('murid');

        // Act as the regular user
        $this->actingAs($regularUser);

        // Attempt to create a user through DataAdmin component
        Livewire::test('data-admin')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('divisi', 'SEO')
            ->set('sekolah', 'Test School')
            ->call('createUser')
            ->assertForbidden();
    }

    /**
     * Test that regular users cannot edit other users
     */
    public function test_regular_user_cannot_edit_other_users(): void
    {
        // Create a regular user
        $regularUser = User::factory()->create();
        $regularUser->assignRole('murid');

        // Create another user to edit
        $targetUser = User::factory()->create();

        // Act as the regular user
        $this->actingAs($regularUser);

        // Attempt to edit another user through DataAdmin component
        Livewire::test('data-admin')
            ->call('edit', $targetUser->id)
            ->assertForbidden();
    }

    /**
     * Test that regular users cannot update other users
     */
    public function test_regular_user_cannot_update_other_users(): void
    {
        // Create a regular user
        $regularUser = User::factory()->create();
        $regularUser->assignRole('murid');

        // Create another user to update
        $targetUser = User::factory()->create();

        // Act as the regular user
        $this->actingAs($regularUser);

        // Attempt to update another user through DataAdmin component
        Livewire::test('data-admin')
            ->set('selectedUserId', $targetUser->id)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->set('divisi', 'Project')
            ->set('sekolah', 'Updated School')
            ->call('updateUser')
            ->assertForbidden();
    }

    /**
     * Test that admins can create users
     */
    public function test_admin_can_create_user_via_data_admin(): void
    {
        // Create an admin user
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Act as the admin
        $this->actingAs($adminUser);

        // Create a user through DataAdmin component
        Livewire::test('data-admin')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('divisi', 'SEO')
            ->set('sekolah', 'Test School')
            ->set('mentor_id', null)
            ->call('createUser')
            ->assertHasNoErrors();

        // Verify the user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test that admins can edit users
     */
    public function test_admin_can_edit_users(): void
    {
        // Create an admin user
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Create a target user
        $targetUser = User::factory()->create();

        // Act as the admin
        $this->actingAs($adminUser);

        // Edit a user through DataAdmin component
        Livewire::test('data-admin')
            ->call('edit', $targetUser->id)
            ->assertHasNoErrors();
    }

    /**
     * Test that admins can update users
     */
    public function test_admin_can_update_users(): void
    {
        // Create an admin user
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Create a target user
        $targetUser = User::factory()->create();

        // Act as the admin
        $this->actingAs($adminUser);

        // Update a user through DataAdmin component
        Livewire::test('data-admin')
            ->set('selectedUserId', $targetUser->id)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->set('divisi', 'Project')
            ->set('sekolah', 'Updated School')
            ->set('mentor_id', null)
            ->call('updateUser')
            ->assertHasNoErrors();

        // Verify the user was updated
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test that regular users can only delete their own journals
     */
    public function test_regular_user_can_only_delete_own_journals(): void
    {
        // Create a regular user
        $regularUser = User::factory()->create();
        $regularUser->assignRole('murid');

        // Create another user
        $otherUser = User::factory()->create();
        $otherUser->assignRole('murid');

        // Create journals for both users
        $regularUserJurnal = JurnalUser::factory()->create([
            'user_id' => $regularUser->id,
        ]);

        $otherUserJurnal = JurnalUser::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        // Act as the regular user
        $this->actingAs($regularUser);

        // Attempt to delete another user's journal - should fail
        Livewire::test('jurnal-users')
            ->call('prepareDelete', $otherUserJurnal->id)
            ->assertForbidden();

        // Verify the other user's journal still exists
        $this->assertDatabaseHas('jurnal_users', [
            'id' => $otherUserJurnal->id,
        ]);

        // Delete own journal - should succeed
        Livewire::test('jurnal-users')
            ->call('prepareDelete', $regularUserJurnal->id)
            ->assertHasNoErrors();

        // Verify the own journal was deleted
        $this->assertDatabaseMissing('jurnal_users', [
            'id' => $regularUserJurnal->id,
        ]);
    }

    /**
     * Test that admins can delete any journal
     */
    public function test_admin_can_delete_any_journal(): void
    {
        // Create an admin user
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Create a regular user
        $regularUser = User::factory()->create();
        $regularUser->assignRole('murid');

        // Create a journal for the regular user
        $userJurnal = JurnalUser::factory()->create([
            'user_id' => $regularUser->id,
        ]);

        // Act as the admin
        $this->actingAs($adminUser);

        // Delete the user's journal - should succeed
        Livewire::test('jurnal-users')
            ->call('prepareDelete', $userJurnal->id)
            ->assertHasNoErrors();

        // Verify the journal was deleted
        $this->assertDatabaseMissing('jurnal_users', [
            'id' => $userJurnal->id,
        ]);
    }

    /**
     * Test that unauthenticated users cannot access protected functions
     */
    public function test_unauthenticated_users_cannot_access_protected_functions(): void
    {
        // Attempt to access DataAdmin component without authentication
        // Note: Livewire components may not throw exceptions for unauthenticated users
        // Instead, we verify that no user is created when unauthenticated
        Livewire::test('data-admin')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('divisi', 'SEO')
            ->set('sekolah', 'Test School')
            ->set('mentor_id', null)
            ->call('createUser');

        // Verify that no user was created (the authorization check should have prevented it)
        $this->assertDatabaseMissing('users', [
            'email' => 'test@example.com',
        ]);
    }
}
