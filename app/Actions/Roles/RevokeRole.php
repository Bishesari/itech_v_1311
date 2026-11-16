<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Enums\RoleScope;
use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class RevokeRole
{
    public function handle(
        Person $person,
        Role $role,
        ?int $membershipId = null,
    ): bool {
        if (! $person->exists || ! $role->exists) {
            throw new InvalidArgumentException(
                'شخص یا نقش انتخاب‌شده معتبر نیست.'
            );
        }
        if (
            $role->scope === RoleScope::Branch &&
            $membershipId === null
        ) {
            throw new InvalidArgumentException(
                'برای لغو نقش شعبه‌ای، شناسه عضویت الزامی است.'
            );
        }

        if (
            $role->scope !== RoleScope::Branch &&
            $membershipId !== null
        ) {
            throw new InvalidArgumentException(
                'نقش‌های غیرشعبه‌ای به عضویت شعبه وابسته نیستند.'
            );
        }

        return DB::transaction(function () use (
            $person,
            $role,
            $membershipId,
        ) {
            return RoleAssignment::query()
                ->where('person_id', $person->id)
                ->where('role_id', $role->id)
                ->where('membership_id', $membershipId)
                ->where('is_active', true)
                ->update(['is_active' => false]) > 0;
        });
    }
}
