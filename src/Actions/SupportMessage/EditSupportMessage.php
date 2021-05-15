<?php


namespace Nikservik\AdminSupport\Actions\SupportMessage;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class EditSupportMessage
{
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-support.route') . '/message/{message}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function asController(SupportMessage $message)
    {
        return view('admin-support::edit', [
            'supportMessage' => $message,
            'user' => $message->user,
        ]);
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $message = $request->route()->parameter('message');

        if ($message->type !== 'supportMessage') {
            $validator->errors()->add('message', 'cant_update_user_message');
        }
    }
}
