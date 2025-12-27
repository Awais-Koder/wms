<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeographicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Pakistan
        $pakistan = Country::create([
            'name' => 'Pakistan',
        ]);

        // Create Punjab province
        $punjab = Province::create([
            'name' => 'Punjab',
            'country_id' => $pakistan->id,
        ]);

        // Create Bahawalpur district
        District::create([
            'name' => 'Bahawalpur',
            'province_id' => $punjab->id,
        ]);
    }
}
