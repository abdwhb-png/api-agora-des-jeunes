<?php

namespace App\Providers;

use App\Services\AiService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

class AiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AiService::class, function (Application $app) {
            return new AiService($app->make($app['config']['ai']['service']));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [AiService::class];
    }
}
