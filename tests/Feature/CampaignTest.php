<?php

namespace Tests\Feature;

use App\Models\Campaign;
use Tests\TestCase;

final class CampaignTest extends TestCase
{
    public function test_campaigns_visible_on_index(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->whileLoggedIn()->get('/campaigns');
        $response->assertStatus(200);
        $response->assertSee($campaign->name);
    }

    public function test_campaigns_index_paginated_at_100_campaigns(): void
    {
        $campaigns = Campaign::factory()->count(500)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/campaigns');
        $response->assertSee('page=5');
        $response->assertDontSee($campaigns[140]->name);

        $response = $this->get('/campaigns?page=2');
        $response->assertSee($campaigns[140]->name);
    }

    public function test_campaigns_index_can_be_searched(): void
    {
        $campaigns = Campaign::factory()->count(500)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/campaigns');
        $response->assertDontSee($campaigns[105]->name);

        $response = $this->get('/campaigns?search=' . $campaigns[105]->name);
        $response->assertSee("/campaigns/{$campaigns[105]->id}");
        $response->assertSee('value="' . e($campaigns[105]->name) . '"', false);
    }

    public function test_campaign_can_be_viewed(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->whileLoggedIn()->get("/campaigns/{$campaign->id}");
        $response->assertStatus(200);
        $response->assertSee($campaign->name);
    }

    public function test_campaign_can_be_edited(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->whileLoggedIn()->get("/campaigns/{$campaign->id}/edit");
        $response->assertStatus(200);
        $response->assertSee($campaign->name);
        $response->assertSee('Save');
    }

    public function test_campaign_can_be_saved(): void
    {
        $campaign = Campaign::factory()->create();

        $details = [
            'name' => 'My new internal campaign',
        ];

        $response = $this->whileLoggedIn()->followingRedirects()->put("/campaigns/{$campaign->id}", $details);
        $response->assertSee('My new internal campaign');
        $response->assertSee('Campaign updated!');
        $this->assertDatabaseHas('campaigns', array_merge($details, ['id' => $campaign->id]));
    }

    public function test_campaign_can_be_deleted(): void
    {
        $campaign = Campaign::factory()->create();

        $this->assertDatabaseHas('campaigns', ['id' => $campaign->id]);

        $response = $this->whileLoggedIn()->delete("/campaigns/{$campaign->id}");
        $response->assertRedirect(route('campaigns.index'));

        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
    }

    public function test_new_campaign_view(): void
    {
        $response = $this->whileLoggedIn()->get('/campaigns/create');
        $response->assertStatus(200);
        $response->assertSee('Create new campaign');
        $response->assertDontSeeText('Delete');
    }

    public function test_campaign_create_request(): void
    {
        $details = [
            'name' => 'My new internal campaign',
        ];
        $response = $this->whileLoggedIn()->followingRedirects()->post('/campaigns', $details);

        $response->assertSee('Campaign created');
        $this->assertDatabaseHas('campaigns', $details);
    }
}
