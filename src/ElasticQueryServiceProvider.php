<?php

namespace Ensi\LaravelElasticQuery;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ElasticQueryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-elastic-query');

        $this->app->scoped(
            ElasticClient::class,
            fn (Application $app) => ElasticClient::fromConfig($app['config']['laravel-elastic-query.connection'])
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-elastic-query.php'),
            ], 'config');
        }
    }
}
