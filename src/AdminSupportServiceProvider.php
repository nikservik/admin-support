<?php

namespace Nikservik\AdminSupport;

use Illuminate\Support\ServiceProvider;

class AdminSupportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/admin-support.php', 'admin-support');
    }

    public function boot()
    {
        $this->loadRoutes();
        $this->loadViews();
        $this->loadTranslations();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/admin-support.php' => config_path('admin-support.php'),
        ], 'admin-support-config');
    }

    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin-support');
    }

    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'admin-support');
    }
}
