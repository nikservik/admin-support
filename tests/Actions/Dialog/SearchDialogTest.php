<?php


namespace Nikservik\AdminSupport\Tests\Actions\Dialog;

use App\Models\User;
use Nikservik\AdminSupport\Actions\Dialog\SearchDialog;
use Nikservik\AdminSupport\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class SearchDialogTest extends TestCase
{
    public function testHandleEmpty()
    {
        $dialogs = SearchDialog::run('test');

        $this->assertCount(0, $dialogs);
    }

    public function testHandle()
    {
        $user = User::factory()->hasSupportMessages(5)->create();
        SupportMessage::factory()->for($user)->create(['message' => 'test message']);

        $dialogs = SearchDialog::run('test');

        $this->assertCount(1, $dialogs);
    }

    public function test_search_dialog_empty()
    {
        $this->actingAs($this->admin)
            ->get('/support/search?q=test')
            ->assertOk()
            ->assertViewHas('dialogs');
    }

    public function test_search_dialog()
    {
        $users = User::factory()->count(3)->hasSupportMessages(5)->create();
        SupportMessage::factory()->for($users[0])->create(['message' => 'test message']);

        $this->actingAs($this->admin)
            ->get('/support/search?q=test')
            ->assertOk()
            ->assertSee($users[0]->name)
            ->assertDontSee($users[1]->name)
            ->assertDontSee($users[2]->name);
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get('/support/search?q=test')
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get('/support/search?q=test')
            ->assertRedirect('/login');
    }
}
