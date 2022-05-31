<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\MailList;
use App\Models\Send;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SendTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_visible_on_campaign()
    {
        $campaign = Campaign::factory()->create();
        $send = Send::factory()->for($campaign)->create();

        $response = $this->whileLoggedIn()->get(route('campaigns.show', compact('campaign')));
        $response->assertStatus(200);
        $response->assertSee($send->name);
    }

    public function test_sends_list_in_campaign_paginated_at_100_sends()
    {
        $campaign = Campaign::factory()->create();
        $sends = Send::factory()->count(500)
            ->for($campaign)->create()
            ->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get(route('campaigns.show', compact('campaign')));
        $response->assertSee('page=5');
        $response->assertDontSee($sends[140]->name);

        $response = $this->get(route('campaigns.show', [
            'campaign' => $campaign, 'page' => 2,
        ]));
        $response->assertSee($sends[140]->name);
    }

    public function test_send_can_be_viewed()
    {
        $send = Send::factory()->create();

        $response = $this->whileLoggedIn()->get("/sends/{$send->id}");
        $response->assertStatus(200);
        $response->assertSee($send->name);
    }

    public function test_send_can_be_edited()
    {
        $send = Send::factory()->create();

        $response = $this->whileLoggedIn()->get("/sends/{$send->id}/edit");
        $response->assertStatus(200);
        $response->assertSee($send->name);
        $response->assertSee('Save');
    }

    public function test_send_can_be_saved()
    {
        $send = Send::factory()->create();

        $details = [
            'name'    => 'My new internal send',
            'subject' => 'My new subject',
            'content' => 'Custom content',
        ];

        $response = $this->whileLoggedIn()->followingRedirects()->put("/sends/{$send->id}", $details);
        $response->assertSee('My new internal send');
        $response->assertSee('Send updated!');
        $this->assertDatabaseHas('sends', array_merge($details, ['id' => $send->id]));
    }

    public function test_send_can_be_deleted()
    {
        $send = Send::factory()->create();
        $campaign = $send->campaign;

        $this->assertDatabaseHas('sends', ['id' => $send->id]);

        $response = $this->whileLoggedIn()->delete("/sends/{$send->id}");
        $response->assertRedirect(route('campaigns.show', compact('campaign')));

        $this->assertDatabaseMissing('sends', ['id' => $send->id]);
    }

    public function test_new_send_view()
    {
        $response = $this->whileLoggedIn()->get('/sends/create');
        $response->assertStatus(200);
        $response->assertSee('Create new send');
        $response->assertDontSeeText('Delete');
    }

    public function test_send_create_request()
    {
        $campaign = Campaign::factory()->create();
        $list = MailList::factory()->create();
        $details = [
            'name'         => 'My new internal send',
            'subject'      => 'My new subject',
            'content'      => 'Custom content',
            'campaign_id'  => $campaign->id,
            'mail_list_id' => $list->id,
        ];
        $response = $this->whileLoggedIn()->followingRedirects()->post('/sends', $details);

        $response->assertSee('Send created');
        $this->assertDatabaseHas('sends', array_merge($details, ['activated_at' => null]));
    }

    public function test_create_send_via_copy()
    {
        /** @var Send $existingSend */
        $existingSend = Send::factory()->create(['name' => 'send to copy']);
        $response = $this->whileLoggedIn()->get("/sends/create?copy_from={$existingSend->id}");

        $response->assertStatus(200);
        $response->assertSee('Create new send');
        $response->assertSee('send to copy');
        $response->assertSee($existingSend->subject);
    }
}
