<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::factory(),
            'weight' => fake()->numberBetween(-10000, 10000),
            'date' => fake()->date(),
            'user_id' => User::factory(),
        ];
    }
}
