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
        MobilePerson::query()->updateOrCreate([
            
        ]);
    }
}
