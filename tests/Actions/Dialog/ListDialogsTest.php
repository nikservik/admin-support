<?php

namespace Nikservik\AdminSupport\Tests\Actions\Dialog;

use App\Models\User;
use Nikservik\AdminSupport\Actions\Dialog\ListDialogs;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class ListDialogsTest extends TestCase
{
    public function testHandleEmpty()
    {
        $dialogs = ListDialogs::run('all', 1, 10);

        $this->assertCount(0, $dialogs);
    }

    public function testHandle()
    {
        User::factory()->count(3)->hasSupportMessages(5)->create();

        $dialogs = ListDialogs::run('all', 1, 10);

        $this->assertCount(3, $dialogs);
    }

    public function testHandleOnlyWithMessages()
    {
        User::factory()->hasSupportMessages(5)->create();
        User::factory()->create();

        $dialogs = ListDialogs::run('all', 1, 10);

        $this->assertCount(1, $dialogs);
    }

    public function test_list_dialogs_empty()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->admin)
            ->get('/support')
            ->assertOk();
    }

    public function test_list_dialogs()
    {
        $users = User::factory()->count(3)->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->get('/support')
            ->assertOk()
            ->assertSee($users[0]->name)
            ->assertSee($users[1]->name)
            ->assertSee($users[2]->name);
    }

    public function test_list_dialogs_all()
    {
        $users = User::factory()->count(3)->hasSupportMessages(5)->create();

        $this->actingAs($this->admin)
            ->get('/support/all')
            ->assertOk()
            ->assertSee($users[0]->name)
            ->assertSee($users[1]->name)
            ->assertSee($users[2]->name);
    }

    public function test_list_dialogs_unread()
    {
        $users = User::factory()->count(3)->has(
            SupportMessage::factory()->count(5)->read()
        )->create();

        $this->actingAs($this->admin)
            ->get('/support/unread')
            ->assertOk()
            ->assertDontSee($users[0]->name)
            ->assertDontSee($users[1]->name)
            ->assertDontSee($users[2]->name);
    }

    public function test_list_dialogs_unread_only()
    {
        $userWithReadMessages = User::factory()->has(
            SupportMessage::factory()->count(5)->read()
        )->create();
        $userWithUnreadMessages = User::factory()->has(
            SupportMessage::factory()->count(5)
        )->create();

        $this->actingAs($this->admin)
            ->get('/support/unread')
            ->assertOk()
            ->assertDontSee($userWithReadMessages->name)
            ->assertSee($userWithUnreadMessages->name);
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get('/support')
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get('/support')
            ->assertRedirect('/login');
    }
}
