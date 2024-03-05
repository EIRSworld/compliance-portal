<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Country;
use App\Models\User;
use App\Policies\CountryPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        Country::class => CountryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
//        Gate::before(function (User $user, string $ability) {
//            return $user->isSuperAdmin() ? true: null;
//        });
    }
}
