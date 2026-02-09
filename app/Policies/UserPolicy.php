<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for User model authorization
 * 
 * This policy enforces role-based access control for user management operations.
 * Only users with 'admin' role can perform CRUD operations on other users.
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $currentUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $currentUser, User $targetUser)
    {
        // Admins can view any user
        if ($currentUser->hasRole('admin')) {
            return true;
        }

        // Users can view their own profile
        return $currentUser->id === $targetUser->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $currentUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $currentUser, User $targetUser)
    {
        // Admins can update any user
        if ($currentUser->hasRole('admin')) {
            return true;
        }

        // Users can update their own profile (limited fields only)
        return $currentUser->id === $targetUser->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $currentUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $currentUser, User $targetUser)
    {
        // Only admins can delete users
        return $currentUser->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $currentUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $currentUser, User $targetUser)
    {
        return $currentUser->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $currentUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $currentUser, User $targetUser)
    {
        return $currentUser->hasRole('admin');
    }

    /**
     * Determine whether the user can manage other users (admin operations).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageUsers(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can assign roles.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function assignRoles(User $user)
    {
        return $user->hasRole('admin');
    }
}
