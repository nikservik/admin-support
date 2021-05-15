<?php


namespace Nikservik\AdminSupport\Actions\Notification;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class UpdateNotification
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::patch(
            '/' . Config::get('admin-support.route') . '/notifications/{notification}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

}
