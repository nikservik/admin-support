<?php


namespace Nikservik\AdminSupport\Actions\SupportMessage;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Nikservik\AdminDashboard\Middleware\AdminMiddleware;
use Nikservik\SimpleSupport\Models\SupportMessage;

class CreateSupportMessage
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::post(
            '/' . Config::get('admin-support.route') . '/dialog/{user}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(string $message, User $user)
    {
        return $user->supportMessages()
            ->save(new SupportMessage([
                'message' => $message,
                'user_id' => $user->id,
                'type' => 'supportMessage',
            ]));
    }

    public function asController(User $user, ActionRequest $request)
    {
        $this->handle($request->get('message'), $user);

        return redirect("/support/dialog/{$user->id}#read");
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'min:2'],
        ];
    }

    public function getValidationMessages(): array
    {
        return [
            'message.required' => 'message_required',
            'message.min' => 'message_min',
        ];
    }
}
