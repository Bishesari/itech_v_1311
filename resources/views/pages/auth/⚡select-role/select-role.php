<?php

use App\Enums\RoleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::auth')]
#[Title('انتخاب نقش')]
class extends Component
{
    public ?int $selectedAssignmentId = null;

    private function getAssignments()
    {
        if (! Auth::user()->is_active) {
            return collect();
        }

        return Auth::user()->person
            ->roleAssignments()
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
            ->get();
    }

    public function select(int $assignmentId): void
    {
        $assignment = $this->getAssignments()
            ->firstWhere('id', $assignmentId);

        if (! $assignment) {
            $this->addError(
                'selectedAssignmentId',
                'نقش انتخاب‌شده معتبر نیست.',
            );

            return;
        }

        $this->selectedAssignmentId = $assignment->id;

        $this->resetValidation('selectedAssignmentId');
    }

    public function confirm(): void
    {
        $assignment = $this->getAssignments()
            ->firstWhere('id', $this->selectedAssignmentId);

        if (! $assignment) {
            $this->selectedAssignmentId = null;

            $this->addError(
                'selectedAssignmentId',
                'لطفاً یک نقش معتبر انتخاب کنید.',
            );

            return;
        }

        session()->put([
            'selected_role_code' => $assignment->role->code->value,
            'selected_role_assignment_id' => $assignment->id,
            'selected_membership_id' => $assignment->membership_id,
        ]);

        $this->redirectIntended(
            default: route('dashboard'),
            navigate: true,
        );
    }

    #[Computed]
    public function assignments()
    {
        return $this->getAssignments();
    }

    #[Computed]
    public function selectedAssignment()
    {
        return $this->assignments
            ->firstWhere('id', $this->selectedAssignmentId);
    }
};
