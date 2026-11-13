<?php

namespace Database\Seeders;

use App\Models\MobilePerson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobilePersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MobilePerson::query()->updateOrCreate(
            [
                'person_id' => 1,
                'mobile_id' => 1,
            ],
            [
                'is_primary' => true,
                'is_verified' => true,
            ],
        );

        MobilePerson::query()->updateOrCreate(
            [
                'person_id' => 1,
                'mobile_id' => 2,
            ],
            [
                'is_primary' => false,
                'is_verified' => true,
            ],
        );

        MobilePerson::query()->updateOrCreate(
            [
                'person_id' => 2,
                'mobile_id' => 3,
            ],
            [
                'is_primary' => true,
                'is_verified' => true,
            ],
        );
        MobilePerson::query()->updateOrCreate(
            [
                'person_id' => 2,
                'mobile_id' => 4,
            ],
            [
                'is_primary' => false,
                'is_verified' => true,
            ],
        );

        MobilePerson::query()->updateOrCreate(
            [
                'person_id' => 3,
                'mobile_id' => 5,
            ],
            [
                'is_primary' => true,
                'is_verified' => true,
            ],
        );
    }
}
