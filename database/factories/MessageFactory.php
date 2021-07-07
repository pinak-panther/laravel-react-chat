<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'user_from'=>$this->faker->randomElement([1,2,3]),
            'user_to'=>$this->faker->randomElement([1,2,3]),
            'message'=>$this->faker->sentence,
            'status'=>$this->faker->randomElement([1,0]),
        ];
    }
}
