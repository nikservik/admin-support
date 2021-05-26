<?php


namespace Nikservik\AdminSupport\Tests\Actions\Dialog;

use App\Models\User;
use Carbon\Carbon;
use Nikservik\AdminSupport\Actions\Dialog\ShowDialog;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class ShowDialogTest extends TestCase
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

    public function test_handle_dont_mark_as_read()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        ShowDialog::run($user);

        $this->assertGreaterThan(
            0,
            $user->supportMessages()
                ->where('type', 'userMessage')
                ->whereNull('read_at')
                ->count()
        );
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

    public function test_show_dialog_opened_true()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertViewHas('opened', true);
    }

    public function test_show_dialog_opened_false()
    {
        $user = User::factory()->has(
            SupportMessage::factory()->count(5)->state(['read_at' => Carbon::now()])
        )->create();

        $this->actingAs($this->admin)
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertViewHas('opened', false);
    }

    public function test_show_dialog_stores_return_url()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->from('/support')
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertSessionHas('return-url');
    }

    public function test_show_dialog_dont_stores_return_url_from_dialog_pagination()
    {
        $user = User::factory()->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->from('/support/dialog/' . $user->id . '?page=2')
            ->get('/support/dialog/' . $user->id)
            ->assertOk()
            ->assertSessionMissing('return-url');
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
