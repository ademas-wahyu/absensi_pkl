<?php

namespace App\Providers;

use App\Models\JurnalUser;
use App\Models\User;
use App\Policies\JurnalUserPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Authentication Service Provider
 * 
 * Registers all application policies for authorization checks.
 * Policies define the authorization logic for model actions.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        JurnalUser::class => JurnalUserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
