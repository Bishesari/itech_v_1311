<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'name' => 'فناوری اطلاعات',
                'slug' => 'information-technology',
            ],
            [
                'name' => 'امور مالی و بازرگانی',
                'slug' => 'finance-and-commerce',
            ],
            [
                'name' => 'هنرهای تجسمی',
                'slug' => 'visual-arts',
            ],
        ];

        foreach ($fields as $field) {
            Field::updateOrCreate(
                ['name' => $field['name']],
                ['slug' => $field['slug']],
            );
        }
    }
}
