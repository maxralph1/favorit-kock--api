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
        return [
            'user_id' => User::all()->random()->id,
            'order_annuled' => fake()->boolean(),
            'delivered' => fake()->boolean(),
            'total_amount' => fake()->randomFloat(2, 0.10, 500) || null,
            'paid' => fake()->boolean(),
        ];
    }
}
