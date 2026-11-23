<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'identity' => '2063531218',
                'username' => 'Yasser',
                'password' => '673673',
                'is_active' => true,
            ],
            [
                'identity' => '3500984886',
                'username' => 'Neda',
                'password' => '14611461',
                'is_active' => true,
            ],
            [
                'identity' => '3501151284',
                'username' => 'Leila',
                'password' => '13611361',
                'is_active' => true,
            ],
            [
                'identity' => '0012345678',
                'username' => 'Ali',
                'password' => '123456',
                'is_active' => true,
            ],
            [
                'identity' => '0012345679',
                'username' => 'Maryam',
                'password' => '123456',
                'is_active' => false,
            ],
        ];

        foreach ($users as $item) {
            $person = Person::where('identity', $item['identity'])
                ->firstOrFail();

            User::updateOrCreate(
                ['username' => $item['username']],
                [
                    'person_id' => $person->id,
                    'password' => $item['password'],
                    'password_is_temporary' => true,
                    'is_active' => $item['is_active'],
                ],
            );
        }
    }
}
