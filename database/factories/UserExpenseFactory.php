<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => fake()->regexify('[A-Za-z0-9]{20}'),
            'amount' => fake()->randomFloat(2, 0, 999999.99),
            'details' => fake()->text(),
            'user_id' => User::factory(),
        ];
    }
}
