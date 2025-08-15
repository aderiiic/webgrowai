<?php

namespace App\Providers;

use App\Models\AiContent;
use App\Policies\AiContentPolicy;
use App\Support\CurrentCustomer;
use App\Support\Usage;
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
        $this->app->singleton(Usage::class, fn() => new Usage());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn($user) => $user->isAdmin());
        Gate::policy(AiContent::class, AiContentPolicy::class);
    }
}
