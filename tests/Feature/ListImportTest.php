<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\MailList;
use Tests\TestCase;

final class ListImportTest extends TestCase
{
    public function test_list_show_has_link_to_import(): void
    {
        $list = MailList::factory()->create();
        $response = $this->whileLoggedIn()->get("/lists/{$list->id}");
        $response->assertSee("/lists/{$list->id}/import");
    }

    public function test_list_import_page_visible(): void
    {
        $list = MailList::factory()->create();
        $response = $this->whileLoggedIn()->get("/lists/{$list->id}/import");

        $response->assertOk();
        $response->assertSee('Import');
        $response->assertSee($list->name);
    }

    public function test_list_import_imports_as_expected(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create();
        $this->assertDatabaseMissing('contacts', ['email' => 'test@example.com']);
        $this->assertDatabaseMissing('contacts', ['email' => 'cat@example.com']);

        $response = $this->whileLoggedIn()->post("/lists/{$list->id}/import", [
            'email_list' => "test@example.com\ncat@example.com",
        ]);
        $response->assertRedirect("/lists/{$list->id}");

        $response = $this->followRedirects($response);
        $response->assertSee('Imported 2 contacts. 2 new, 0 existing.');

        $this->assertDatabaseHas('contacts', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('contacts', ['email' => 'cat@example.com']);

        $this->assertEquals(2, $list->contacts()->count());
        $contact = $list->contacts()->first();

        $this->assertNotNull($contact->updated_at);
        $this->assertTrue(abs(time() - $contact->updated_at->timestamp) < 50);
        $this->assertTrue(abs(time() - $contact->created_at->timestamp) < 50);
    }

    public function test_list_import_does_not_create_duplicates_for_existing_contacts(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create();
        $contactA = Contact::factory()->create(['email' => 'test@example.com']);
        $contactB = Contact::factory()->create(['email' => 'cat@example.com']);
        $list->contacts()->sync([$contactA->id, $contactB->id]);

        $this->assertEquals(2, $list->contacts()->count());

        $response = $this->whileLoggedIn()->followingRedirects()->post("/lists/{$list->id}/import", [
            'email_list' => " teSt@example.com\n cAt@exaMple.COM ",
        ]);
        $response->assertOk();
        $response->assertSee('Imported 2 contacts. 0 new, 2 existing.');
        $this->assertEquals(2, $list->contacts()->count());
        $this->assertEquals(1, Contact::query()->where('email', '=', 'test@example.com')->count());
    }

    public function test_import_does_not_detach_existing_contacts_on_list(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create();
        $contactA = Contact::factory()->create(['email' => 'test@example.com']);
        $contactB = Contact::factory()->create(['email' => 'cat@example.com']);
        $list->contacts()->sync([$contactA->id, $contactB->id]);

        $this->assertEquals(2, $list->contacts()->count());

        $this->whileLoggedIn()->followingRedirects()->post("/lists/{$list->id}/import", [
            'email_list' => 'barry@example.com',
        ])->assertOk();
        $this->assertEquals(3, $list->contacts()->count());
    }

    public function test_import_skips_invalid_emails(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create();
        $response = $this->whileLoggedIn()->followingRedirects()->post("/lists/{$list->id}/import", [
            'email_list' => "a\n@@.sp\ntest@cat@dog.donkey\nhi\"there\"@test.com",
        ]);
        $response->assertOk();
        $response->assertSee('Imported 0 contacts. 0 new, 0 existing.');
        $this->assertEquals(0, $list->contacts()->count());
    }

    public function test_import_handles_duplicates(): void
    {
        /** @var MailList $list */
        $list = MailList::factory()->create();
        $response = $this->whileLoggedIn()->followingRedirects()->post("/lists/{$list->id}/import", [
            'email_list' => "test@example.com\nTEST@Example.com",
        ]);
        $response->assertOk();
        $response->assertSee('Imported 1 contacts. 1 new, 0 existing.');
        $this->assertEquals(1, $list->contacts()->count());
    }
}
