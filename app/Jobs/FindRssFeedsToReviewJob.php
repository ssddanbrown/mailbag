<?php

namespace App\Jobs;

use App\Models\RssFeed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Finds pending RSS feeds to process and creates new
 * jobs for each that needs to be processed.
 */
class FindRssFeedsToReviewJob implements ShouldBeUnique, ShouldQueue
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
        $activeFeeds = RssFeed::query()
            ->where('active', '=', true)
            ->where('next_review_at', '<', now())
            ->get();

        $activeFeeds->each(function ($feed) {
            dispatch(new ReviewRssFeedJob($feed));
        });
    }
}
