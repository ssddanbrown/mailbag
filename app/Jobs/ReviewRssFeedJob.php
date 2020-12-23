<?php

namespace App\Jobs;

use App\Models\RssFeed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReviewRssFeedJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $feed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RssFeed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->feed->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->feed->isPending()) {
            return;
        }

        // TODO - Do send stuff
        // TODO - Filter updates between the actual dates we're working with
        //  So between last_reviewed_at and next_review_at
        //  So we don't cause trouble with the below and re-check the same date range
        //  in future.

        $this->feed->last_reviewed_at = now();
        $this->feed->updateNextReviewDate();
        $this->feed->save();
    }
}
