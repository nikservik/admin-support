<?php


namespace Nikservik\AdminSupport\Actions\Dialog;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class ShowDialog
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-support.route') . '/dialog/{user}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(User $user)
    {
        $this->markAsRead($user);

        return $user->supportMessages()
            ->latest()
            ->paginate(Config::get('admin-support.messages-per-page'));
    }

    public function asController(User $user)
    {
        return view('admin-support::show', [
            'messages' => $this->handle($user),
            'user' => $user,
        ]);
    }

    protected function markAsRead(User $user): void
    {
        $user->supportMessages()
            ->where('type', 'userMessage')
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }
}
