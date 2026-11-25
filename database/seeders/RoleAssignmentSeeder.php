<?php

namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Models\Membership;
use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
use Illuminate\Database\Seeder;

class RoleAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            [
                'identity' => '2063531218',
                'role' => RoleCode::SuperAdmin,
                'branch_code' => null,
            ],
            [
                'identity' => '3500984886',
                'role' => RoleCode::Founder,
                'branch_code' => null,
            ],
            [
                'identity' => '3501151284',
                'role' => RoleCode::Instructor,
                'branch_code' => 'BU001',
            ],
        ];

        foreach ($assignments as $item) {
            $person = Person::where('identity', $item['identity'])
                ->firstOrFail();

            $role = Role::where('code', $item['role']->value)
                ->firstOrFail();

            $membershipId = null;

            if ($item['branch_code'] !== null) {
                $membershipId = Membership::query()
                    ->where('person_id', $person->id)
                    ->whereHas('branch', fn ($query) => $query
                        ->where('code', $item['branch_code'])
                    )
                    ->firstOrFail()
                    ->id;
            }

            RoleAssignment::updateOrCreate(
                [
                    'person_id' => $person->id,
                    'role_id' => $role->id,
                    'membership_id' => $membershipId,
                ],
                [
                    'is_active' => true,
                ],
            );
        }
    }
}
