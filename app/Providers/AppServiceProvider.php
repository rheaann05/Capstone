<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Gate::policy(Role::class, RolePolicy::class);

        Gate::before(function ($user, $ability, $models) {
            
            if (isset($models[0]) && $models[0] instanceof Role) {
                return null; 
            }

            return $user->hasRole('super-admin') ? true : null;
        });
    }
}