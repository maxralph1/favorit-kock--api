<?php

namespace Database\Factories;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealImage>
 */
class MealImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meal_id' => Meal::all()->random()->id,
            'image_url' => fake()->imageUrl(rand(50, 500), rand(50, 500), 'meals', true, 'Faker'),
        ];
    }
}
