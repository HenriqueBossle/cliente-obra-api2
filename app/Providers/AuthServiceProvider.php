<?php

namespace App\Providers;

use App\Models\Construction;
use App\Policies\ConstructionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Construction::class => ConstructionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin();
        });
    }
}