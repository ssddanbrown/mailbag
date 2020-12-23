<?php

namespace Tests\Feature;

use App\Jobs\ScrubUnsubscribesJob;
use App\Models\Contact;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class DeleteUnsubscribedContactsTest extends TestCase
{
    public function test_job_deletes_unsubscribed_contacts()
    {
        $weekAgo = now()->subDays(7);
        $subscribedContact = Contact::factory()->subscribed()->create(['updated_at' => $weekAgo]);
        $unsubscribedContact = Contact::factory()->unsubscribed()->create(['updated_at' => $weekAgo]);

        dispatch_now(new ScrubUnsubscribesJob());

        $this->assertDatabaseHas('contacts', ['id' => $subscribedContact->id]);
        $this->assertDatabaseMissing('contacts', ['id' => $unsubscribedContact->id]);
    }

    public function test_job_ignores_contacts_with_recent_changes()
    {
        $recent = now()->subHours(22);
        $unsubscribedContact = Contact::factory()->unsubscribed()->create(['updated_at' => $recent]);

        dispatch_now(new ScrubUnsubscribesJob());

        $this->assertDatabaseHas('contacts', ['id' => $unsubscribedContact->id]);
    }

    public function test_command_calls_job()
    {
        Bus::fake();
        Artisan::call('mailbag:scrub-unsubscribes');
        Bus::assertDispatchedTimes(ScrubUnsubscribesJob::class, 1);
    }
}
