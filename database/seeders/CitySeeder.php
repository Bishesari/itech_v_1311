<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'bushehr' => [
                ['name' => 'بوشهر', 'slug' => 'bushehr'],
            ],
        ];
        foreach ($data as $provinceSlug => $cities) {

            $province = Province::where('slug', $provinceSlug)->first();

            if (! $province) {
                continue;
            }
            foreach ($cities as $city) {
                City::updateOrCreate(
                    [
                        'province_id' => $province->id,
                        'slug' => $city['slug'],
                    ],
                    [
                        'name' => $city['name'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
