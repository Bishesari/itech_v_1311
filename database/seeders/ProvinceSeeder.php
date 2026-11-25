<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'بوشهر', 'slug' => 'bushehr']
        ];

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['slug' => $province['slug']],
                $province
            );
        }
    }
}
