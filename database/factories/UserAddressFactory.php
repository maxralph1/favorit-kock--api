<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
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
            'house_number' => fake()->buildingNumber(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'post_code' => fake()->postcode(),
            'state' => fake()->state(),
            'landmark' => fake()->text(100),
        ];
    }
}
