<?php

namespace Database\Seeders;

use App\Models\Mobile;
use Illuminate\Database\Seeder;

class MobileSeeder extends Seeder
{
    public function run(): void
    {
        $mobiles = [
            '09177755924',
            '09034336111',
            '09350568163',
            '09177729312',
            '09173751261',
        ];

        foreach ($mobiles as $number) {
            Mobile::updateOrCreate(
                ['number' => $number],
            );
        }
    }
}
