<?php

namespace App\Providers;

use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrentCustomer::class, fn() => new CurrentCustomer());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn($user) => $user->isAdmin());
    }
}
