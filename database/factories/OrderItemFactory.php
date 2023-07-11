<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $meal = Meal::all()->random();

        return [
            'meal_id' => $meal->id,
            'order_id' => Order::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'amount_due' => $meal->price,
            // 'amount_due' => fake()->randomFloat(2, 0.10, 120),
            'quantity_ordered' => rand(1, 10),
        ];
    }
}
