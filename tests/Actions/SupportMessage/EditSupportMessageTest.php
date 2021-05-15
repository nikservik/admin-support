<?php

namespace Nikservik\AdminSupport\Tests\Actions\SupportMessage;

use App\Models\User;
use Nikservik\AdminSupport\Actions\SupportMessage\EditSupportMessage;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class EditSupportMessageTest extends TestCase
{

    public function test_edit_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->withoutExceptionHandling();
        $this->actingAs($this->admin)
            ->get("/support/message/{$message->id}")
            ->assertOk()
            ->assertSee($message->message);
    }

    public function test_dont_edit_user_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->get("/support/message/{$message->id}")
            ->assertStatus(302)
            ->assertSessionHasErrors('message');
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->get("/support/message/{$message->id}")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->user)
            ->get("/support/message/{$message->id}")
            ->assertRedirect('/login');
    }

}
