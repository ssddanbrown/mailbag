<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->email,
            'unsubscribed' => rand(0, 1) === 0,
        ];
    }

    public function unsubscribed()
    {
        return $this->state(function(array $attributes) {
             return [
                 'unsubscribed' => true,
             ];
        });
    }

    public function subscribed()
    {
        return $this->state(function(array $attributes) {
            return [
                'unsubscribed' => false,
            ];
        });
    }
}
