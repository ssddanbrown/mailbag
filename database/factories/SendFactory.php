<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\MailList;
use App\Models\Send;
use Illuminate\Database\Eloquent\Factories\Factory;

class SendFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Send::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Send ' . $this->faker->state . ' ' . $this->faker->month,
            'content' => implode("\n\n", $this->faker->paragraphs(3)),
            'subject' => $this->faker->company . ' ' . $this->faker->sentence,
            'mail_list_id' => MailList::factory(),
            'campaign_id' => Campaign::factory(),
        ];
    }
}
