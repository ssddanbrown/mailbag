<?php

namespace Tests\Feature;

use App\Models\Contact;
use Tests\TestCase;

final class ContactTest extends TestCase
{
    public function test_contacts_visible_on_index(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertStatus(200);
        $response->assertSee($contact->email);
    }

    public function test_contacts_index_paginated_at_100_contacts(): void
    {
        $contacts = Contact::factory()->count(500)->create()->sortBy('email')->values();

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertSee('page=5');
        $response->assertDontSee($contacts[140]->email);

        $response = $this->get('/contacts?page=2');
        $response->assertSee($contacts[140]->email);
    }

    public function test_contacts_index_can_be_searched(): void
    {
        $contacts = Contact::factory()->count(500)->create()->sortBy('email')->values();

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertDontSee($contacts[105]->email);

        $response = $this->get('/contacts?search=' . $contacts[105]->email);
        $response->assertSee("/contacts/{$contacts[105]->id}");
        $response->assertSee("value=\"{$contacts[105]->email}\"", false);
    }

    public function test_contact_can_be_edited(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->whileLoggedIn()->get("/contacts/{$contact->id}");
        $response->assertSee($contact->email);
        $response->assertSee('Save');
    }

    public function test_contact_can_be_saved(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->whileLoggedIn()->followingRedirects()->put("/contacts/{$contact->id}", [
            'email'        => 'updated@example.com',
            'unsubscribed' => '1',
        ]);
        $response->assertSee('updated@example.com');
        $response->assertSee('Contact updated!');
        $this->assertDatabaseHas('contacts', [
            'id'           => $contact->id,
            'email'        => 'updated@example.com',
            'unsubscribed' => true,
        ]);
    }

    public function test_contact_can_be_deleted(): void
    {
        $contact = Contact::factory()->create();

        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);

        $response = $this->whileLoggedIn()->delete("/contacts/{$contact->id}");
        $response->assertRedirect(route('contacts.index'));

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_new_contact_view(): void
    {
        $response = $this->whileLoggedIn()->get('/contacts/create');
        $response->assertStatus(200);
        $response->assertSee('Create new contact');
        $response->assertDontSeeText('Delete');
    }

    public function test_contact_create_request(): void
    {
        $response = $this->whileLoggedIn()->followingRedirects()->post('/contacts', [
            'email' => 'barry@example.com',
        ]);

        $response->assertSee('barry@example.com');
        $response->assertSee('Contact created');
        $this->assertDatabaseHas('contacts', [
            'email'        => 'barry@example.com',
            'unsubscribed' => false,
        ]);
    }
}
