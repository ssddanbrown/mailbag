<?php

namespace Tests\Feature;

use App\Jobs\FindRssFeedsToReviewJob;
use App\Jobs\ReviewRssFeedJob;
use App\Jobs\SendActivationJob;
use App\Models\RssFeed;
use App\Services\Rss\RssArticle;
use App\Services\Rss\RssParser;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class RssFeedReviewTest extends TestCase
{
    public function test_find_rss_feeds_to_review_jobs_correctly_creates_review_jobs_for_pending_feeds(): void
    {
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subMonth(),
            'send_frequency'   => 7,
            'next_review_at'   => now()->subDays(15),
            'active'           => true,
        ]);
        $nonPendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subDay(),
            'send_frequency'   => 7,
            'next_review_at'   => now()->addHour(),
            'active'           => true,
        ]);
        Bus::fake();

        (new FindRssFeedsToReviewJob())->handle();
        Bus::assertDispatchedTimes(ReviewRssFeedJob::class, 1);
    }

    public function test_find_rss_feeds_to_review_job_ignores_inactive_feeds(): void
    {
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => now()->subMonth(),
            'send_frequency'   => 7,
            'next_review_at'   => now()->subDays(15),
            'active'           => false,
        ]);
        Bus::fake();

        (new ReviewRssFeedJob($pendingFeed))->handle(app(RssParser::class));
        Bus::assertNotDispatched(ReviewRssFeedJob::class);
    }

    public function test_review_rss_feed_job_does_nothing_if_feed_inactive(): void
    {
        $lastReviewed = now()->subMonth();
        /** @var RssFeed $pendingFeed */
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => $lastReviewed,
            'send_frequency'   => 7,
            'next_review_at'   => now()->subDays(15),
            'active'           => false,
        ]);
        Bus::fake();

        (new ReviewRssFeedJob($pendingFeed))->handle(app(RssParser::class));
        $pendingFeed->refresh();
        $this->assertEquals($lastReviewed->unix(), $pendingFeed->last_reviewed_at->unix());
        Bus::assertNotDispatched(SendActivationJob::class);
    }

    public function test_review_rss_feed_job_does_nothing_if_feed_not_due(): void
    {
        $lastReviewed = now()->subHour();
        /** @var RssFeed $pendingFeed */
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => $lastReviewed,
            'send_frequency'   => 7,
            'next_review_at'   => now()->subDays(15),
            'active'           => true,
        ]);
        Bus::fake();

        (new ReviewRssFeedJob($pendingFeed))->handle(app(RssParser::class));
        $pendingFeed->refresh();
        $this->assertEquals($lastReviewed->unix(), $pendingFeed->last_reviewed_at->unix());
        Bus::assertNotDispatched(SendActivationJob::class);
    }

    public function test_review_rss_feed_job_updates_feed_check_time_if_due(): void
    {
        $lastReviewed = now()->subDays(10);
        /** @var RssFeed $pendingFeed */
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => $lastReviewed,
            'send_frequency'   => 7,
            'active'           => true,
        ]);
        Bus::fake();

        $mockParser = $this->mock(RssParser::class);
        $mockParser->shouldReceive('getArticles')->andReturn(collect());

        (new ReviewRssFeedJob($pendingFeed))->handle($mockParser);
        $pendingFeed->refresh();

        $this->assertTrue($lastReviewed->unix() < $pendingFeed->last_reviewed_at->unix());
        $this->assertTrue($pendingFeed->next_review_at > now()->addDays(5) && $pendingFeed->next_review_at < now()->addDays(8));
        Bus::assertDispatchedTimes(SendActivationJob::class, 0);
    }

    public function test_review_rss_feed_job_updates_creates_send_with_content_if_due(): void
    {
        $lastReviewed = now()->subDays(10);
        /** @var RssFeed $pendingFeed */
        $pendingFeed = RssFeed::factory()->create([
            'last_reviewed_at' => $lastReviewed,
            'send_frequency'   => 7,
            'active'           => true,
        ]);
        $pendingFeed->templateSend->update([
            'content' => 'RSS Test {{rss_loop}}{{rss_article_title}}{{rss_article_link}}{{end_rss_loop}}',
        ]);
        Bus::fake();

        $mockParser = $this->mock(RssParser::class);
        $mockParser->shouldReceive('getArticles')->andReturn(collect([
            new RssArticle('Rss Article', 'https://example.com/1', '', now()),
        ]));

        (new ReviewRssFeedJob($pendingFeed))->handle($mockParser);
        $pendingFeed->refresh();

        $this->assertTrue($lastReviewed->unix() < $pendingFeed->last_reviewed_at->unix());
        $this->assertTrue($pendingFeed->next_review_at > now()->addDays(5) && $pendingFeed->next_review_at < now()->addDays(8));
        Bus::assertDispatchedTimes(SendActivationJob::class, 1);
        $latestSend = $pendingFeed->campaign->sends()->orderBy('id', 'desc')->first();
        $this->assertStringContainsString('Rss Article', $latestSend->content);
        $this->assertStringContainsString('RSS Test', $latestSend->content);
        $this->assertStringContainsString('https://example.com/1', $latestSend->content);
    }
}
