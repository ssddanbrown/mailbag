<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\RssFeed;
use App\Models\Send;
use Tests\TestCase;

final class RssFeedTest extends TestCase
{
    public function test_feed_shows_on_feed_view(): void
    {
        /** @var RssFeed $feed */
        $feed = RssFeed::factory()->create();

        $resp = $this->whileLoggedIn()->get(route('campaigns.show', ['campaign' => $feed->campaign]));
        $resp->assertSee($feed->url);
    }

    public function test_feed_can_be_edited(): void
    {
        $feed = RssFeed::factory()->create();

        $response = $this->whileLoggedIn()->get("/campaigns/{$feed->campaign->id}/rss/{$feed->id}");
        $response->assertStatus(200);
        $response->assertSee($feed->name);
        $response->assertSee('Save');
    }

    public function test_feed_can_be_saved(): void
    {
        /** @var RssFeed $feed */
        $feed = RssFeed::factory()->create();

        $details = [
            'active'           => '1',
            'url'              => 'https://example.com/feed.xml',
            'campaign_id'      => $feed->campaign->id,
            'template_send_id' => $feed->templateSend->id,
            'send_frequency'   => 7,
            'target_hour'      => 15,
        ];

        $response = $this->whileLoggedIn()->put("/campaigns/{$feed->campaign->id}/rss/{$feed->id}", $details);
        $response->assertRedirect("/campaigns/{$feed->campaign->id}");

        $response = $this->followRedirects($response);
        $response->assertSee('https://example.com/feed.xml');
        $response->assertSee('RSS feed updated!');
        $this->assertDatabaseHas('rss_feeds', array_merge($details, ['id' => $feed->id]));
    }

    public function test_feed_can_be_deleted(): void
    {
        /** @var RssFeed $feed */
        $feed = RssFeed::factory()->create();
        $campaign = $feed->campaign;

        $this->assertDatabaseHas('rss_feeds', ['id' => $feed->id]);

        $response = $this->whileLoggedIn()->delete("/campaigns/{$feed->campaign->id}/rss/{$feed->id}");
        $response->assertRedirect(route('campaigns.show', compact('campaign')));

        $this->assertDatabaseMissing('rss_feeds', ['id' => $feed->id]);
    }

    public function test_new_feed_view(): void
    {
        $campaign = Campaign::factory()->create();
        $response = $this->whileLoggedIn()->get("/campaigns/{$campaign->id}/rss/create");
        $response->assertStatus(200);
        $response->assertSee('Create new RSS Feed');
        $response->assertSee('Feed URL');
        $response->assertDontSeeText('Delete');
    }

    public function test_feed_create_request(): void
    {
        $campaign = Campaign::factory()->create();
        $send = Send::factory()->create(['campaign_id' => $campaign->id]);

        $details = [
            'active'           => '1',
            'url'              => 'https://example.com/feed.xml',
            'campaign_id'      => $campaign->id,
            'template_send_id' => $send->id,
            'send_frequency'   => 7,
            'target_hour'      => 13,
        ];
        $response = $this->whileLoggedIn()->followingRedirects()->post("/campaigns/{$campaign->id}/rss", $details);

        $response->assertSee('RSS feed created');
        $this->assertDatabaseHas('rss_feeds', $details);
    }
}
