<?php


namespace Nikservik\AdminSupport\Actions\Dialog;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;

class SearchDialog
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get('/' . Config::get('admin-support.route') . '/search', static::class)
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(string $query)
    {
        return User::select(['users.*', 'support_messages.message as message'])
            ->join('support_messages', 'users.id', '=', 'support_messages.user_id')
            ->where('support_messages.message', 'LIKE', '%'.$query.'%')
            ->orderBy('support_messages.created_at', 'DESC')
            ->paginate(Config::get('admin-support.messages-per-page'))
            ->appends(['q' => $query]);
    }

    public function asController(ActionRequest $request)
    {
        $query = $request->get('q');

        $dialogs = $this->handle($query);

        return view('admin-support::index', [
            'dialogs' => $dialogs,
            'list' => 'search',
            'query' => $query,
            'stats' => $this->stats(['search' => $dialogs->total()]),
        ]);
    }

    protected function stats(array $stats): array
    {
        return array_merge($stats, [
            'all' => User::has('supportMessages')->count(),
            'unread' => User::whereHas('supportMessages', function ($query) {
                $query->where('type', 'userMessage')->whereNull('read_at');
            })->count(),
        ]);
    }
}
