<?php


namespace Nikservik\AdminSupport\Tests\Actions\Dialog;


use App\Models\User;
use Nikservik\AdminSupport\Actions\Dialog\ShowDialog;

class ShowDialogTest extends \Nikservik\AdminSupport\Tests\TestCase
{
    public function testHandleEmpty()
    {
        $user = User::factory()->create();

        $messages = ShowDialog::run($user);

        $this->assertCount(0, $messages);
    }

    public function testHandle()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        $messages = ShowDialog::run($user);

        $this->assertCount(5, $messages);
    }

    public function test_show_dialog_empty()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertViewHas('messages');
    }

    public function test_show_dialog()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertSee($user->supportMessages[0]->message)
            ->assertSee($user->supportMessages[4]->message);
    }

    public function test_redirect_when_not_authenticated()
    {
        $user = User::factory()->create();

        $this->get('/support/dialog/' . $user->id)
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $user = User::factory()->create();

        $this->actingAs($this->user)
            ->get('/support/dialog/' . $user->id)
            ->assertRedirect('/login');
    }

    public function test_redirect_when_user_not_exists()
    {
        $this->actingAs($this->admin)
            ->get('/support/dialog/111')
            ->assertStatus(404);
    }
}
