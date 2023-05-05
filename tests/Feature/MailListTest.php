<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\MailList;
use Tests\TestCase;

final class MailListTest extends TestCase
{
    public function test_lists_visible_on_index(): void
    {
        $list = MailList::factory()->create();

        $response = $this->whileLoggedIn()->get('/lists');
        $response->assertStatus(200);
        $response->assertSee($list->name);
    }

    public function test_lists_index_paginated_at_100_lists(): void
    {
        $lists = MailList::factory()->count(500)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/lists');
        $response->assertSee('page=5');
        $response->assertDontSee($lists[140]->name);

        $response = $this->get('/lists?page=2');
        $response->assertSee($lists[140]->name);
    }

    public function test_lists_index_can_be_searched(): void
    {
        $lists = MailList::factory()->count(500)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/lists');
        $response->assertDontSee($lists[105]->name);

        $response = $this->get('/lists?search=' . $lists[105]->name);
        $response->assertSee("/lists/{$lists[105]->id}");
        $response->assertSee('value="' . e($lists[105]->name) . '"', false);
    }

    public function test_can_see_contacts_on_list_show_page(): void
    {
        /** @var Contact $contact */
        $contact = Contact::factory()->create();
        $list = MailList::factory()->create();
        $contact->lists()->sync([$list->id]);

        $response = $this->whileLoggedIn()->get("/lists/{$list->id}");
        $response->assertSee($contact->email);
    }

    public function test_can_see_list_details_on_show_page(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create([
            'name'         => 'My test list',
            'description'  => 'A test list',
            'display_name' => 'List display name',
            'slug'         => 'a-list-slug',
        ]);

        $response = $this->whileLoggedIn()->get("/lists/{$list->id}");
        $response->assertSee('My test list');
        $response->assertSee('A test list');
        $response->assertSee('List display name');
        $response->assertSee('http://localhost/signup/a-list-slug');
    }

    public function test_list_can_be_edited(): void
    {
        $list = MailList::factory()->create();

        $response = $this->whileLoggedIn()->get("/lists/{$list->id}/edit");
        $response->assertSee($list->name);
        $response->assertSee('Save');
    }

    public function test_list_can_be_saved(): void
    {
        $list = MailList::factory()->create();

        $details = [
            'name'         => 'My new internal list',
            'display_name' => 'My cool list',
            'slug'         => 'my_list',
        ];

        $response = $this->whileLoggedIn()->followingRedirects()->put("/lists/{$list->id}", $details);
        $response->assertSee('My new internal list');
        $response->assertSee('List updated!');
        $this->assertDatabaseHas('mail_lists', array_merge($details, ['id' => $list->id]));
    }

    public function test_list_can_be_deleted(): void
    {
        $list = MailList::factory()->create();

        $this->assertDatabaseHas('mail_lists', ['id' => $list->id]);

        $response = $this->whileLoggedIn()->delete("/lists/{$list->id}");
        $response->assertRedirect(route('lists.index'));

        $this->assertDatabaseMissing('mail_lists', ['id' => $list->id]);
    }

    public function test_new_list_view(): void
    {
        $response = $this->whileLoggedIn()->get('/lists/create');
        $response->assertStatus(200);
        $response->assertSee('Create new list');
        $response->assertDontSeeText('Delete');
    }

    public function test_list_create_request(): void
    {
        $details = [
            'name'         => 'My new internal list',
            'display_name' => 'My cool list',
        ];
        $response = $this->whileLoggedIn()->followingRedirects()->post('/lists', $details);

        $response->assertSee('List created');
        $this->assertDatabaseHas('mail_lists', array_merge($details, ['slug' => 'my-cool-list']));
    }
}
