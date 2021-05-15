<?php


namespace Nikservik\AdminSupport\Tests\Actions\SupportMessage;


use App\Models\User;
use Nikservik\AdminSupport\Actions\SupportMessage\DeleteSupportMessage;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class DeleteSupportMessageTest extends TestCase
{
    public function testHandle()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        DeleteSupportMessage::run($message);

        $this->assertCount(0, $user->supportMessages);
    }

    public function test_delete_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->admin)
            ->get("/support/message/{$message->id}/delete")
            ->assertStatus(302);

        $this->assertCount(0, $user->supportMessages);
    }

    public function test_redirect_when_message_not_exists()
    {
        $this->actingAs($this->admin)
            ->get('/support/message/111/delete')
            ->assertStatus(404);
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->get("/support/message/{$message->id}/delete")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromSupport()->create();

        $this->actingAs($this->user)
            ->get("/support/message/{$message->id}/delete")
            ->assertRedirect('/login');
    }

    public function test_dont_delete_user_message()
    {
        $user = User::factory()->create();
        $message = SupportMessage::factory()->for($user)->fromUser()->create();

        $this->actingAs($this->admin)
            ->get("/support/message/{$message->id}/delete")
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertCount(1, $user->supportMessages);
    }

}
