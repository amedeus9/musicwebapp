<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Tanzania', 'iso_code' => 'TZ', 'slug' => 'tanzania', 'region' => 'East Africa'],
            ['name' => 'Kenya', 'iso_code' => 'KE', 'slug' => 'kenya', 'region' => 'East Africa'],
            ['name' => 'Uganda', 'iso_code' => 'UG', 'slug' => 'uganda', 'region' => 'East Africa'],
            ['name' => 'Rwanda', 'iso_code' => 'RW', 'slug' => 'rwanda', 'region' => 'East Africa'],
            ['name' => 'Burundi', 'iso_code' => 'BI', 'slug' => 'burundi', 'region' => 'East Africa'],
            ['name' => 'DR Congo', 'iso_code' => 'CD', 'slug' => 'dr-congo', 'region' => 'Central Africa'],
            ['name' => 'South Africa', 'iso_code' => 'ZA', 'slug' => 'south-africa', 'region' => 'Southern Africa'],
            ['name' => 'Nigeria', 'iso_code' => 'NG', 'slug' => 'nigeria', 'region' => 'West Africa'],
            ['name' => 'Ghana', 'iso_code' => 'GH', 'slug' => 'ghana', 'region' => 'West Africa'],
            ['name' => 'United States', 'iso_code' => 'US', 'slug' => 'united-states', 'region' => 'North America'],
            ['name' => 'United Kingdom', 'iso_code' => 'GB', 'slug' => 'united-kingdom', 'region' => 'Europe'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['name' => $country['name']], $country);
        }
    }
}
