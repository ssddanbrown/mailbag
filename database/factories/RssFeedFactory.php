<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Send;
use Illuminate\Database\Eloquent\Factories\Factory;

class RssFeedFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $campaign = Campaign::factory()->create();

        return [
            'active'           => true,
            'url'              => $this->faker->url() . '.xml',
            'campaign_id'      => $campaign->id,
            'template_send_id' => Send::factory()->for($campaign),
            'send_frequency'   => 7,
            'target_hour'      => 12,
            'next_review_at'   => now()->addDays(7),
            'last_reviewed_at' => now(),
        ];
    }
}
