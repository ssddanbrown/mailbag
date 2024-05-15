<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Send;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SendRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory()->subscribed(),
            'send_id'    => Send::factory(),
            'sent_at'    => rand(0, 1) === 1 ? null : $this->faker->dateTimeThisDecade(),
            'key'        => Str::random(16) . '-' . Str::random(16),
        ];
    }
}
