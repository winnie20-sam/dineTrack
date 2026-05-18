<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Shared\CustomConstants;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-access', fn($user) => in_array($user->role_id, [
            CustomConstants::ROLE_ADMIN,
            CustomConstants::ROLE_OWNER,
            CustomConstants::ROLE_MANAGER,
        ]));

        Gate::define('staff-access', fn($user) => $user->role_id === CustomConstants::ROLE_STAFF);
    }
}
