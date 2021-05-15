<?php


namespace Nikservik\AdminSupport\Actions\Notification;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class CreateNotification
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::post(
            '/' . Config::get('admin-support.route') . '/notifications',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }
}
