<?php

namespace VendorName\Skeleton\Tests;

use Illuminate\Support\ServiceProvider;

class TestViewsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'admin-dashboard');
    }
}
