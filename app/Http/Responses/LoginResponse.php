<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\RoleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

final class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        if (! $request->user()->is_active) {
            $request->session()->forget([
                'selected_role_code',
                'selected_role_assignment_id',
                'selected_membership_id',
            ]);

            $request->session()->put([
                'selected_role_code' => 'newcomer',
                'selected_role_assignment_id' => null,
                'selected_membership_id' => null,
            ]);

            return redirect()->intended(route('dashboard'));
        }

        $person = $request->user()->person;

        $assignments = $person?->roleAssignments()
            ->where('is_active', true)
            ->whereHas('role', fn (Builder $query) => $query
                ->where('is_active', true)
            )
            ->where(function (Builder $query) {
                $query->where(function (Builder $branch) {
                    $branch
                        ->whereHas('role', fn (Builder $role) => $role
                            ->where('scope', RoleScope::Branch->value)
                        )
                        ->whereHas('membership', fn (Builder $membership) => $membership
                            ->where('is_active', true)
                            ->whereHas('branch', fn (Builder $branch) => $branch
                                ->where('is_active', true)
                            )
                        );
                })->orWhere(function (Builder $global) {
                    $global
                        ->whereHas('role', fn (Builder $role) => $role
                            ->whereIn('scope', [
                                RoleScope::System->value,
                                RoleScope::Institute->value,
                            ])
                        )
                        ->whereNull('membership_id');
                });
            })
            ->with(['role', 'membership.branch'])
            ->get() ?? collect();

        // پاک‌کردن زمینه قبلی
        $request->session()->forget([
            'selected_role_code',
            'selected_role_assignment_id',
            'selected_membership_id',
        ]);

        // کاربر تازه‌وارد
        if ($assignments->isEmpty()) {
            $request->session()->put([
                'selected_role_code' => 'newcomer',
                'selected_role_assignment_id' => null,
                'selected_membership_id' => null,
            ]);

            return redirect()->intended(route('dashboard'));
        }

        // بیش از یک زمینه دسترسی
        if ($assignments->count() > 1) {
            return redirect()->route('roles.select');
        }

        // دقیقاً یک زمینه دسترسی
        $assignment = $assignments->first();

        $request->session()->put([
            'selected_role_code' => $assignment->role->code,
            'selected_role_assignment_id' => $assignment->id,
            'selected_membership_id' => $assignment->membership_id,
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
