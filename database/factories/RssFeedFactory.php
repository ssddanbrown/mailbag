<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\RssFeed;
use App\Models\Send;
use Illuminate\Database\Eloquent\Factories\Factory;

class RssFeedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RssFeed::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $campaign = Campaign::factory()->create();
        return [
            'active' => true,
            'url' => $this->faker->url . '.xml',
            'campaign_id' => $campaign->id,
            'template_send_id' => Send::factory()->for($campaign),
            'send_frequency' => 7,
        ];
    }
}
