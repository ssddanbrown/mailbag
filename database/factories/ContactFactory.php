<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email'        => $this->faker->unique()->email(),
            'unsubscribed' => rand(0, 1) === 0,
        ];
    }

    public function unsubscribed()
    {
        return $this->state(function (array $attributes) {
            return [
                'unsubscribed' => true,
            ];
        });
    }

    public function subscribed()
    {
        return $this->state(function (array $attributes) {
            return [
                'unsubscribed' => false,
            ];
        });
    }
}
