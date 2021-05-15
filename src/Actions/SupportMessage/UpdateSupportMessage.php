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

class UpdateSupportMessage
{
    use AsObject;
    use AsController;

    public static function route(): void
    {
        Route::patch(
            '/' . Config::get('admin-support.route') . '/message/{message}',
            static::class
        )
            ->middleware(['web', 'auth', AdminMiddleware::class]);
    }

    public function handle(SupportMessage $message, string $updated)
    {
        $message->update(['message' => $updated]);
    }

    public function asController(SupportMessage $message, ActionRequest $request)
    {
        $this->handle($message, $request->get('message'));

        return redirect("/support/dialog/{$message->user_id}#read");
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

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $message = $request->route()->parameter('message');

        if ($message->type !== 'supportMessage') {
            $validator->errors()->add('message', 'cant_update_user_message');
        }
    }
}
