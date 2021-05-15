<?php


namespace Nikservik\AdminSupport\Tests\Actions\SupportMessage;

use App\Models\User;
use Nikservik\AdminSupport\Actions\SupportMessage\UpdateSupportMessage;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class UpdateSupportMessageTest extends TestCase
{
    public function testHandle()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        UpdateSupportMessage::run($message, 'new text');

        $this->assertEquals('new text', $message->message);
    }

    public function test_update_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->patch("/support/message/{$message->id}", [
                'message' => 'new text',
            ])
            ->assertStatus(302);

        $this->assertEquals('new text', $message->refresh()->message);
    }

    public function test_dont_update_empty_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->patch("/support/message/{$message->id}", [
                'message' => '',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertNotEquals('', $message->refresh()->message);
    }

    public function test_dont_update_user_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->patch("/support/message/{$message->id}", [
                'message' => 'new text',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertNotEquals('new text', $message->refresh()->message);
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->patch("/support/message/{$message->id}")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->user)
            ->patch("/support/message/{$message->id}")
            ->assertRedirect('/login');
    }
}
