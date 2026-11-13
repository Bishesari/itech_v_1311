<?php

namespace Database\Seeders;

use App\Models\Mobile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mobile::query()->updateOrCreate(
            [
                'number' => '09177755924',
            ]
        );
        Mobile::query()->updateOrCreate(
            [
                'number' => '09034336111',
            ]
        );
        Mobile::query()->updateOrCreate(
            [
                'number' => '09350568163',
            ]
        );
        Mobile::query()->updateOrCreate(
            [
                'number' => '09177729312',
            ]
        );
        Mobile::query()->updateOrCreate(
            [
                'number' => '09173751261',
            ]
        );
    }
}
