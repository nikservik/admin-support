<?php


namespace Nikservik\AdminSupport\Actions\SupportMessage;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class DeleteSupportMessage
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::get(
            '/' . Config::get('admin-support.route') . '/message/{message}/delete',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(SupportMessage $message)
    {
        $message->delete();
    }

    public function asController(SupportMessage $message)
    {
        $this->handle($message);

        return redirect()->back();
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $message = $request->route()->parameter('message');

        if ($message->type !== 'supportMessage') {
            $validator->errors()->add('message', 'cant_delete_user_message');
        }
    }
}
