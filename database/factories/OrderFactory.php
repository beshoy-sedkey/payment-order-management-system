<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user  = User::factory()->create();
        return   [
            'user_id' => $user->id,
            'product_name' => $this->faker->text(5),
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 200, 5000),
            'status' => $this->faker->randomElement(['pending' , 'paid' , 'canceled'])
        ];
    }
}
