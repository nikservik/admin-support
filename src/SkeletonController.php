<?php

namespace VendorName\Skeleton;

use Illuminate\Support\Facades\Route;

class SkeletonController
{
    public static function routes()
    {
        Route::namespace('VendorName\Skeleton')
            ->prefix('package_prefix')->group(function () {
                Route::get('', 'SkeletonController@index');

            });
    }

    public function index()
    {
        return view('package_slug::index');
    }
}
