<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * This job deletes unsubscribed contacts from the database.
 * It adds a buffer of a day since last change so that any accidental
 * changes can be responded to within that time before the contact is deleted.
 */
class ScrubUnsubscribesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Contact::query()
            ->where('unsubscribed', '=', true)
            ->where('updated_at', '<', now()->subDay())
            ->chunk(500, function (Collection $contacts) {
                $contacts->each(function (Contact $contact) {
                    $contact->deleteWithRelations();
                });
            });
    }
}
