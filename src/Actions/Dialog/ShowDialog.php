<?php


namespace Nikservik\AdminSupport\Actions\Dialog;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Actions\GetSupportMessages;
use Nikservik\SimpleSupport\Models\SupportMessage;

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
        return SupportMessage::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere(fn ($query) =>
                    $query->whereNull('user_id')
                        ->where('type', 'notification')
                );
            })
            ->where('type', '<>', 'notificationRead')
            ->latest()
            ->paginate(Config::get('admin-support.messages-per-page'));
    }

    public function asController(User $user)
    {
        $previous = URL::previous();
        if (! Str::contains($previous, '/dialog/')) {
            session(['return-url' => $previous]);
        }

        return view('admin-support::show', [
            'messages' => $this->handle($user),
            'user' => $user,
            'opened' => $user->supportMessages()
                ->where('type', 'userMessage')
                ->whereNull('read_at')
                ->count() > 0,
        ]);
    }
}
