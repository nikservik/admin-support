<?php

namespace Nikservik\AdminSupport\Tests\Actions\Dialog;

use App\Models\User;
use Nikservik\AdminSupport\Actions\Dialog\CloseDialog;
use Nikservik\AdminSupport\Tests\TestCase;

class CloseDialogTest extends TestCase
{
    protected User $dialogUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->dialogUser = User::factory()->hasSupportMessages(5)->create();
    }

    public function testHandle()
    {
        CloseDialog::run($this->dialogUser);

        $this->assertEquals(
            0,
            $this->dialogUser->supportMessages()
                ->where('type', 'userMessage')
                ->whereNull('read_at')
                ->count()
        );
    }

    public function test_marks_as_read()
    {
        $this->actingAs($this->admin)
            ->get("/support/dialog/{$this->dialogUser->id}/close")
            ->assertStatus(302);

        $this->assertEquals(
            0,
            $this->dialogUser->supportMessages()
                ->where('type', 'userMessage')
                ->whereNull('read_at')
                ->count()
        );
    }

    public function test_redirect_on_close()
    {
        $this->actingAs($this->admin)
            ->get("/support/dialog/{$this->dialogUser->id}/close")
            ->assertRedirect("/support/dialog/{$this->dialogUser->id}");
    }

    public function test_redirect_on_close_and_return()
    {
        $this->actingAs($this->admin)
            ->withSession(['return-url' => '/support/unread'])
            ->get("/support/dialog/{$this->dialogUser->id}/close/return")
            ->assertRedirect("/support/unread");
    }

    public function test_redirect_on_close_and_return_without_return_url()
    {
        $this->actingAs($this->admin)
            ->get("/support/dialog/{$this->dialogUser->id}/close/return")
            ->assertRedirect("/support/dialog/{$this->dialogUser->id}");
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get("/support/dialog/{$this->dialogUser->id}/close")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get("/support/dialog/{$this->dialogUser->id}/close")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_user_not_exists()
    {
        $this->actingAs($this->admin)
            ->get('/support/dialog/111/close')
            ->assertStatus(404);
    }

}
