<?php

namespace App\Policies;

use App\Models\JurnalUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for JurnalUser model authorization
 * 
 * This policy enforces role-based access control for journal operations.
 * Admins can manage all journals, while regular users can only manage their own.
 */
class JurnalUserPolicy
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
        // Admins can view all journals
        if ($user->hasRole('admin')) {
            return true;
        }

        // Regular users can view their own journals
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JurnalUser  $jurnalUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, JurnalUser $jurnalUser)
    {
        // Admins can view any journal
        if ($user->hasRole('admin')) {
            return true;
        }

        // Users can view their own journals
        return $user->id === $jurnalUser->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // All authenticated users can create journals
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JurnalUser  $jurnalUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, JurnalUser $jurnalUser)
    {
        // Admins can update any journal
        if ($user->hasRole('admin')) {
            return true;
        }

        // Users can update their own journals
        return $user->id === $jurnalUser->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JurnalUser  $jurnalUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, JurnalUser $jurnalUser)
    {
        // Admins can delete any journal
        if ($user->hasRole('admin')) {
            return true;
        }

        // Users can delete their own journals
        return $user->id === $jurnalUser->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JurnalUser  $jurnalUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, JurnalUser $jurnalUser)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JurnalUser  $jurnalUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, JurnalUser $jurnalUser)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage all journals (admin operations).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageJurnals(User $user)
    {
        return $user->hasRole('admin');
    }
}
