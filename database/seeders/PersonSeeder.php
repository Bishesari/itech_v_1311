<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\Nationality;
use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            [
                'identity' => '2063531218',
                'gender' => Gender::MALE,
                'first_name_fa' => 'یاسر',
                'last_name_fa' => 'بیشه سری',
            ],
            [
                'identity' => '3500984886',
                'gender' => Gender::FEMALE,
                'first_name_fa' => 'ندا',
                'last_name_fa' => 'بخشی زاده',
            ],
            [
                'identity' => '3501151284',
                'gender' => Gender::FEMALE,
                'first_name_fa' => 'سیده لیلا',
                'last_name_fa' => 'امامی',
            ],
        ];

        foreach ($people as $person) {
            Person::updateOrCreate(
                ['identity' => $person['identity']],
                [
                    'nationality' => Nationality::Iranian,
                    'gender' => $person['gender'],
                    'first_name_fa' => $person['first_name_fa'],
                    'last_name_fa' => $person['last_name_fa'],
                ],
            );
        }
    }
}
