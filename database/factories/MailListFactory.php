<?php

namespace Database\Factories;

use App\Models\MailList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MailListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MailList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->country . ' ' . Str::random(5) . ' List';
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'display_name' => 'The great ' . $name,
            'description' => $this->faker->sentence,
        ];
    }
}
