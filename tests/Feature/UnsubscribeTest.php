<?php

namespace Tests\Feature;

use App\Models\MailList;
use App\Models\SendRecord;
use Tests\TestCase;

final class UnsubscribeTest extends TestCase
{
    public function test_unsubscribe_view_accessible(): void
    {
        $sendRecord = SendRecord::factory()->create(['sent_at' => now()]);

        $resp = $this->get("/unsubscribe/{$sendRecord->key}");
        $resp->assertStatus(200);
        $resp->assertSee('Remove me from this list');
        $resp->assertSee($sendRecord->send->maillist->name);
        $resp->assertSee('Unsubscribe from all');
    }

    public function test_unsubscribe_view_shows_warning_on_expired_or_invalid_send(): void
    {
        $sendRecord = SendRecord::factory()->create();
        $sendRecord->sent_at = now()->subMonths(2);
        $sendRecord->save();

        $keys = [$sendRecord->key, 'abc12445asd'];
        foreach ($keys as $key) {
            $resp = $this->get("/unsubscribe/{$key}");
            $resp->assertStatus(404);
            $resp->assertSee('expired');
            $resp->assertSee('invalid');
            $resp->assertDontSee('Unsubscribe from all');
        }
    }

    public function test_unsubscribe_from_list(): void
    {
        /** @var SendRecord $sendRecord */
        $sendRecord = SendRecord::factory()->create();
        $contact = $sendRecord->contact;
        $contact->lists()->sync([$sendRecord->send->maillist->id]);

        $this->assertEquals(1, $contact->lists()->count());

        $resp = $this->post("/unsubscribe/{$sendRecord->key}/list");
        $resp->assertRedirect('/unsubscribe/confirm?type=list');

        $this->assertEquals(0, $contact->lists()->count());
    }

    public function test_unsubscribe_from_all_unsubs_contact_and_removes_from_all_lists(): void
    {
        /** @var SendRecord $sendRecord */
        $sendRecord = SendRecord::factory()->create();
        $lists = MailList::factory()->count(10)->create();
        $contact = $sendRecord->contact;
        $contact->lists()->sync($lists->pluck('id'));

        $this->assertFalse(boolval($contact->unsubscribed));
        $this->assertEquals(10, $contact->lists()->count());

        $resp = $this->post("/unsubscribe/{$sendRecord->key}/all");
        $resp->assertRedirect('/unsubscribe/confirm?type=all');

        $contact->refresh();
        $this->assertTrue(boolval($contact->unsubscribed));
        $this->assertEquals(0, $contact->lists()->count());
    }

    public function test_unsubscribe_thank_you(): void
    {
        $resp = $this->get('/unsubscribe/confirm?type=all');
        $resp->assertSee('Unsubscribe from all');
        $resp->assertDontSee('list');

        $resp = $this->get('/unsubscribe/confirm?type=list');
        $resp->assertSee('You have been removed from the requested list');
        $resp->assertDontSee('from all');
    }
}
