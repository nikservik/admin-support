<?php


namespace Nikservik\AdminSupport\Tests\Actions\SupportMessage;


use App\Models\User;
use Nikservik\AdminSupport\Actions\SupportMessage\CreateSupportMessage;
use Nikservik\AdminSupport\Tests\TestCase;

class CreateSupportMessageTest extends TestCase
{
    public function testHandle()
    {
        $user = User::factory()->create();

        CreateSupportMessage::run('test message', $user, $this->admin);

        $this->assertCount(1, $user->supportMessages);
    }

    public function test_create_message()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->post('/support/dialog/' . $user->id, [
                'message' => 'test message',
            ])
            ->assertStatus(302);

        $this->assertCount(1, $user->supportMessages);
    }

    public function test_dont_create_empty_message()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->post('/support/dialog/' . $user->id, [
                'message' => '',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertCount(0, $user->supportMessages);
    }

    public function test_redirect_when_user_not_exists()
    {
        $this->actingAs($this->admin)
            ->post('/support/dialog/111', [
                'message' => '',
            ])
            ->assertStatus(404);
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();

        $this->post('/support/dialog/' . $user->id, [
                'message' => '',
            ])
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();

        $this->actingAs($this->user)
            ->post('/support/dialog/' . $user->id, [
                'message' => '',
            ])
            ->assertRedirect('/login');
    }

}
