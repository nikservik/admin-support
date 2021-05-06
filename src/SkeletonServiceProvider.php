<?php

namespace VendorName\Skeleton;

use Illuminate\Support\ServiceProvider;

class SkeletonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'package_slug');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'package_slug');
    }
}
