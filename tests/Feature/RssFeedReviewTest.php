<?php

namespace Tests\Feature;

use App\Jobs\FindRssFeedsToReviewJob;
use App\Jobs\ReviewRssFeedJob;
use App\Models\RssFeed;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class RssFeedReviewTest extends TestCase
{

    public function test_find_rss_feeds_to_review_jobs_correctly_creates_review_jobs_for_pending_feeds()
    {
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subMonth(),
            'send_frequency' => 7,
            'next_review_at' => now()->subDays(15),
            'active' => true,
        ]);
        $nonPendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subDay(),
            'send_frequency' => 7,
            'next_review_at' => now()->addHour(),
            'active' => true,
        ]);
        Bus::fake();

        (new FindRssFeedsToReviewJob())->handle();
        Bus::assertDispatchedTimes(ReviewRssFeedJob::class, 1);
    }

    public function test_find_rss_feeds_to_review_jobs_ignores_inactive_feeds()
    {
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subMonth(),
            'send_frequency' => 7,
            'next_review_at' => now()->subDays(15),
            'active' => false,
        ]);
        Bus::fake();

        (new FindRssFeedsToReviewJob())->handle();
        Bus::assertNotDispatched(ReviewRssFeedJob::class);
    }
}
