<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\MailList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactListTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_to_mulitple_lists()
    {
        $lists = MailList::factory()->count(10)->create();
        $contact = Contact::factory()->create();

        $response = $this->whileLoggedIn()->followingRedirects()->put("/contacts/{$contact->id}/lists", [
            'lists' => $lists->pluck('id')->values()->toArray(),
        ]);

        $response->assertStatus(200);
        $response->assertSee('Added contact to 10 lists');
        $this->assertEquals(10, $contact->lists()->count());
    }

    public function test_removing_contact_from_lists()
    {
        $lists = MailList::factory()->count(10)->create();
        $contact = Contact::factory()->create();

        $contact->lists()->sync($lists->pluck('id'));
        $this->assertEquals(10, $contact->lists()->count());

        $response = $this->whileLoggedIn()->followingRedirects()->delete("/contacts/{$contact->id}/lists", [
            'lists' => $lists->pluck('id')->values()->toArray(),
        ]);

        $response->assertStatus(200);
        $response->assertSee('Removed contact from 10 lists');
        $this->assertEquals(0, $contact->lists()->count());
    }
}
