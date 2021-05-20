<?php


namespace Nikservik\AdminSupport\Actions\Dialog;

use App\Models\User;
use Illuminate\Pagination\Paginator;
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

    public function handle(string $list, int $page, int $perPage)
    {
        $messages = $list == 'all'
            ? self::allMessages()
            : self::unreadMessages();

        return User::whereIn('id',
                $messages
                    ->offset(($page - 1) * $perPage)
                    ->limit($perPage)
                    ->get()
            )
            ->with(['supportMessages' => function($query) {
                $query->where('type', 'userMessage')->latest();
            }])
            ->withCount(['supportMessages as unread' => function ($query) {
                $query->where('type', 'userMessage')->whereNull('read_at');
            }])
            ->get();
    }

    public function asController(ActionRequest $request)
    {
        $list = $request->route()->parameter('list') ?? 'all';

        if (! in_array($list, ['all', 'unread'])) {
            $list = 'all';
        }

        $page = $request->get('page', 1);
        $perPage = Config::get('admin-support.messages-per-page');
        $stats = $this->stats();

        $dialogs = new Paginator(
            $this->handle($list, $page, $perPage),
            $perPage,
            $page
        );
        $dialogs->setPath('/' . Config::get('admin-support.route') . '/' . $list);
        $dialogs->hasMorePagesWhen($stats[$list] > $page * $perPage);

        return view('admin-support::index', [
            'dialogs' => $dialogs,
            'list' => $list,
            'stats' => $stats,
        ]);
    }

    public static function stats(): array
    {
        return [
            'all' => self::allMessages()->selectRaw('COUNT(user_id) OVER() as count')->get()[0]->count ?? 0,
            'unread' => self::unreadMessages()->selectRaw('COUNT(user_id) OVER() as count')->get()[0]->count ?? 0,
        ];
    }

    protected static function allMessages()
    {
        return SupportMessage::select('user_id')
            ->where('type', 'userMessage')
            ->orWhere('type', 'supportMessage')
            ->groupBy('user_id')
            ->orderByRaw('MAX(created_at) DESC');
    }

    protected static function unreadMessages()
    {
        return SupportMessage::select('user_id')
            ->where('type', 'userMessage')
            ->whereNull('read_at')
            ->groupBy('user_id')
            ->orderByRaw('MAX(created_at) DESC');
    }
}
