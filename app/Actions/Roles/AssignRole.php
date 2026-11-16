<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Enums\RoleScope;
use App\Models\Membership;
use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class AssignRole
{
    public function handle(
        Person $person,
        Role $role,
        ?Membership $membership = null,
    ): RoleAssignment {
        return DB::transaction(function () use ($person, $role, $membership) {
            if (! $role->is_active) {
                throw new InvalidArgumentException('این نقش غیرفعال است.');
            }

            if (! $person->exists || ! $role->exists) {
                throw new InvalidArgumentException(
                    'شخص یا نقش انتخاب‌شده معتبر نیست.'
                );
            }

            if ($role->scope === RoleScope::Branch) {
                if (! $membership) {
                    throw new InvalidArgumentException(
                        'برای تخصیص نقش شعبه‌ای، عضویت شخص در شعبه الزامی است.'
                    );
                }

                if (
                    ! $membership->exists ||
                    (int) $membership->person_id !== (int) $person->id ||
                    ! $membership->is_active ||
                    ! $membership->branch()->where('is_active', true)->exists()
                ) {
                    throw new InvalidArgumentException(
                        'عضویت انتخاب‌شده معتبر یا فعال نیست.'
                    );
                }
            } elseif ($membership !== null) {
                throw new InvalidArgumentException(
                    'نقش‌های غیرشعبه‌ای نباید به عضویت شعبه متصل باشند.'
                );
            }

            return RoleAssignment::query()->updateOrCreate(
                [
                    'person_id' => $person->id,
                    'role_id' => $role->id,
                    'membership_id' => $membership?->id,
                ],
                [
                    'is_active' => true,
                ],
            );
        });
    }
}
