<?php

namespace App\Providers;

use App\Domain\PublicContent\Contracts\PublicContentSource;
use App\Domain\PublicContent\Infrastructure\StaticPublicContentSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PublicContentSource::class, StaticPublicContentSource::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
