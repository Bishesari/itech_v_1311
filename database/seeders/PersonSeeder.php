<?php

namespace Database\Seeders;

use App\Enums\Nationality;
use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Person::query()->updateOrCreate(
            [
                'identity' => '2063531218',
            ],
            [
                'nationality' => Nationality::Iranian,
                'first_name_fa' => 'یاسر',
                'last_name_fa' => 'بیشه سری',
            ],
        );

        Person::query()->updateOrCreate(
            [
                'identity' => '3500984886',
            ],
            [
                'nationality' => Nationality::Iranian,
                'first_name_fa' => 'ندا',
                'last_name_fa' => 'بخشی زاده',
            ],
        );

        Person::query()->updateOrCreate(
            [
                'identity' => '3501151284',
            ],
            [
                'nationality' => Nationality::Iranian,
                'first_name_fa' => 'سیده لیلا',
                'last_name_fa' => 'امامی',
            ],
        );

    }
}
