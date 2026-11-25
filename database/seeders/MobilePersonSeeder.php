<?php

namespace Database\Seeders;

use App\Models\Mobile;
use App\Models\MobilePerson;
use App\Models\Person;
use Illuminate\Database\Seeder;

class MobilePersonSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [
            [
                'identity' => '2063531218',
                'number' => '09177755924',
                'is_primary' => true,
            ],
            [
                'identity' => '2063531218',
                'number' => '09034336111',
                'is_primary' => false,
            ],
            [
                'identity' => '3500984886',
                'number' => '09350568163',
                'is_primary' => true,
            ],
            [
                'identity' => '3500984886',
                'number' => '09177729312',
                'is_primary' => false,
            ],
            [
                'identity' => '3501151284',
                'number' => '09173751261',
                'is_primary' => true,
            ],
        ];

        foreach ($relations as $item) {
            $person = Person::where('identity', $item['identity'])
                ->firstOrFail();

            $mobile = Mobile::where('number', $item['number'])
                ->firstOrFail();

            MobilePerson::updateOrCreate(
                [
                    'person_id' => $person->id,
                    'mobile_id' => $mobile->id,
                ],
                [
                    'is_primary' => $item['is_primary'],
                    'is_verified' => true,
                ],
            );
        }
    }
}
