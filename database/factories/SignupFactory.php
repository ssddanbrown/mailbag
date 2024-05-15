<?php

namespace Database\Factories;

use App\Models\MailList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SignupFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email'        => $this->faker->email(),
            'mail_list_id' => MailList::factory(),
            'key'          => Str::random(32),
            'attempts'     => 0,
        ];
    }
}
