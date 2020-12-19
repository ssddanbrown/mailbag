<?php

namespace Tests\Feature;

use App\Models\Contact;
use Tests\TestCase;

class ContactTest extends TestCase
{

    public function test_contacts_visible_on_index()
    {
        $contact = Contact::factory()->create();

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertStatus(200);
        $response->assertSee($contact->email);
    }

    public function test_contacts_index_paginated_at_100_contacts()
    {
        $contacts = Contact::factory()->count(500)->create()->sortBy('email')->values();

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertSee('page=5');
        $response->assertDontSee($contacts[140]->email);

        $response = $this->get('/contacts?page=2');
        $response->assertSee($contacts[140]->email);
    }

    public function test_contacts_index_can_be_searched()
    {
        $contacts = Contact::factory()->count(500)->create()->sortBy('email');

        $response = $this->whileLoggedIn()->get('/contacts');
        $response->assertDontSee($contacts[105]->email);

        $response = $this->get('/contacts?search=' . $contacts[105]->email);
        $response->assertSee("/contacts/{$contacts[105]->id}");
        $response->assertSee("value=\"{$contacts[105]->email}\"", false);
    }
}
