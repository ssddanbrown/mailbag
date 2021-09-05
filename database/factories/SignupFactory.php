<?php

namespace Database\Factories;

use App\Models\MailList;
use App\Models\Signup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SignupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Signup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'        => $this->faker->email,
            'mail_list_id' => MailList::factory(),
            'key'          => Str::random(32),
            'attempts'     => 0,
        ];
    }
}
