<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * This job deletes unsubscribed contacts from the database.
 * It adds a buffer of a day since last change so that any accidental
 * changes can be responded to within that time before the contact is deleted.
 */
class DeleteUnsubscribedContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Contact::query()
            ->where('unsubscribed', '=', true)
            ->where('updated_at', '<', now()->subDay())
            ->delete();
    }
}
