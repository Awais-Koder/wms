<?php

namespace Database\Factories;

use App\Models\Tehsil;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'tehsil_id' => Tehsil::factory(),
        ];
    }
}
