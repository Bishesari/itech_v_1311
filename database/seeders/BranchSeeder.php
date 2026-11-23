<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $province = Province::where('slug', 'bushehr')->firstOrFail();

        $city = City::where('province_id', $province->id)
            ->where('slug', 'bushehr')
            ->firstOrFail();

        Branch::updateOrCreate(
            ['code' => 'BU001'],
            [
                'short_name' => 'شعبه مرکزی',
                'is_main' => true,
                'province_id' => $province->id,
                'city_id' => $city->id,
                'address' => 'بوشهر، سنگی، اول گلخونه، ساختمان سیراف',
                'postal_code' => '7514977863',
                'phone' => '07733103350',
                'mobile' => '09350568163',
                'is_active' => true,
            ],
        );

        Branch::updateOrCreate(
            ['code' => 'BU002'],
            [
                'short_name' => 'شعبه یک',
                'is_main' => false,
                'province_id' => $province->id,
                'city_id' => $city->id,
                'address' => 'بوشهر، میدان برج، پشت بیمارستان مادر و کودک',
                'phone' => '07733103350',
                'mobile' => '09034336111',
                'is_active' => true,
            ],
        );
    }
}
