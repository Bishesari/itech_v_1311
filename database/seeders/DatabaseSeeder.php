<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,

            PersonSeeder::class,
            MobileSeeder::class,
            UserSeeder::class,
            MobilePersonSeeder::class,

            BranchSeeder::class,
            MembershipSeeder::class,
            RoleAssignmentSeeder::class,

            FieldSeeder::class,
        ]);
    }
}
