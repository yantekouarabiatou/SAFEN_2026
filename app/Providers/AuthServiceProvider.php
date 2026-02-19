<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Artisan;
use App\Policies\ArtisanPolicy;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Artisan::class => ArtisanPolicy::class,
    ];

    public function boot()
    {
        Gate::define('access-admin', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin'], 'web'); // ou le guard que vous utilisez
        });
        Gate::define('admin', fn($user) => $user->hasRole('admin'));
        $this->registerPolicies();
    }
}
