<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\MailList;
use Illuminate\Database\Eloquent\Factories\Factory;

class SendFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'         => 'Send ' . $this->faker->state() . ' ' . $this->faker->month(),
            'content'      => implode("\n\n", $this->faker->paragraphs(3)),
            'subject'      => $this->faker->company() . ' ' . $this->faker->sentence(),
            'mail_list_id' => MailList::factory(),
            'campaign_id'  => Campaign::factory(),
            'activated_at' => null,
        ];
    }
}
