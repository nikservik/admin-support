<?php


namespace Nikservik\AdminSupport\Actions\Dialog;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class ListDialogs
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get('/' . Config::get('admin-support.route') . '/{list?}', static::class)
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(string $list)
    {
        return User::whereHas('supportMessages', function ($query) use ($list) {
            if ($list == 'unread') {
                $query->where('type', 'userMessage')->whereNull('read_at');
            }
        })
            ->with(['supportMessages' => function ($query) use ($list) {
                $query->where('type', 'userMessage')->latest();
                if ($list == 'unread') {
                    $query->whereNull('read_at');
                }
            }])
            ->withCount(['supportMessages as unread' => function ($query) {
                $query->where('type', 'userMessage')->whereNull('read_at');
            }])
            ->orderByDesc(
                SupportMessage::select('created_at')
                ->whereColumn('user_id', 'users.id')->orderBy('created_at', 'desc')->limit(1)
            )
            ->paginate(Config::get('admin-support.messages-per-page'));
    }

    public function asController(ActionRequest $request)
    {
        $list = $request->route()->parameter('list') ?? 'all';

        if (! in_array($list, ['all', 'unread'])) {
            $list = 'all';
        }

        $dialogs = $this->handle($list);

        return view('admin-support::index', [
            'dialogs' => $dialogs,
            'list' => $list,
            'stats' => $this->stats([$list => $dialogs->total()]),
        ]);
    }

    protected function stats(array $stats): array
    {
        if (! array_key_exists('all', $stats)) {
            $stats['all'] = User::has('supportMessages')->count();
        }

        if (! array_key_exists('unread', $stats)) {
            $stats['unread'] = User::whereHas('supportMessages', function ($query) {
                $query->where('type', 'userMessage')->whereNull('read_at');
            })->count();
        }

        return $stats;
    }
}
