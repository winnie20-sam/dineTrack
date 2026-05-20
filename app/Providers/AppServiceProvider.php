<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Shared\CustomConstants;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Bootstrap pagination
        Paginator::useBootstrap();

        // Gates
        Gate::define('admin-access', fn($user) => in_array($user->role_id, [
            CustomConstants::ROLE_ADMIN,
            CustomConstants::ROLE_OWNER,
            CustomConstants::ROLE_MANAGER,
        ]));

        Gate::define('staff-access', fn($user) => $user->role_id === CustomConstants::ROLE_STAFF);
    }
}
