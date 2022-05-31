<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Send;
use App\Models\SendRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SendRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SendRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contact_id' => Contact::factory()->subscribed(),
            'send_id'    => Send::factory(),
            'sent_at'    => rand(0, 1) === 1 ? null : $this->faker->dateTimeThisDecade(),
            'key'        => Str::random(16).'-'.Str::random(16),
        ];
    }
}
