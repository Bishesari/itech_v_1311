<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Membership;
use App\Models\Person;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $memberships = [
            [
                'identity' => '2063531218',
                'branch_code' => 'BU001',
            ],
            [
                'identity' => '2063531218',
                'branch_code' => 'BU002',
            ],
            [
                'identity' => '3501151284',
                'branch_code' => 'BU001',
            ],
            [
                'identity' => '0012345679',
                'branch_code' => 'BU001',
            ],
        ];

        foreach ($memberships as $item) {
            $person = Person::where('identity', $item['identity'])
                ->firstOrFail();

            $branch = Branch::where('code', $item['branch_code'])
                ->firstOrFail();

            Membership::updateOrCreate(
                [
                    'person_id' => $person->id,
                    'branch_id' => $branch->id,
                ],
                [
                    'is_active' => true,
                ],
            );
        }
    }
}
