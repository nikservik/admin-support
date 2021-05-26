<?php


namespace Nikservik\AdminSupport\Actions\Dialog;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class CloseDialog
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-support.route') . '/dialog/{user}/close/{return?}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    protected function handle(User $user): void
    {
        $user->supportMessages()
            ->where('type', 'userMessage')
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }


    public function asController(User $user, string $return = null)
    {
        $this->handle($user);

        if ($return == 'return' && Session::has('return-url')) {
            return Redirect::to(Session::get('return-url'));
        } else {
            return Redirect::to('/' . Config::get('admin-support.route') . '/dialog/' . $user->id);
        }
    }

}
