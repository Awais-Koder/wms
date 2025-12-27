<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use App\Models\Tehsil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HospitalFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'uuid' => fake()->uuid(),
            'country_id' => Country::factory(),
            'district_id' => District::factory(),
            'tehsil_id' => Tehsil::factory(),
            'city_id' => City::factory(),
            'user_id' => User::factory(),
            'address' => fake()->text(),
            'doctor_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'cnic' => fake()->regexify('[A-Za-z0-9]{20}'),
            'amount' => fake()->randomFloat(2, 0, 999999.99),
            'agreement_duration' => fake()->numberBetween(-10000, 10000),
            'date' => fake()->date(),
            'phc_number' => fake()->regexify('[A-Za-z0-9]{150}'),
            'mobile_number_1' => fake()->regexify('[A-Za-z0-9]{20}'),
            'mobile_number_2' => fake()->regexify('[A-Za-z0-9]{20}'),
            'agreement_image' => fake()->regexify('[A-Za-z0-9]{150}'),
            'province_id' => Province::factory(),
        ];
    }
}
