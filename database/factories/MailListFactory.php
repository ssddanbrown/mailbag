<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MailListFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->country() . ' ' . Str::random(5) . ' List';

        return [
            'name'         => $name,
            'slug'         => Str::slug($name),
            'display_name' => 'The great ' . $name,
            'description'  => $this->faker->sentence(),
        ];
    }
}
