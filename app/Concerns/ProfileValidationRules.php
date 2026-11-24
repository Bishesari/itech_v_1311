<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    protected function profileRules(int $userId): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:25',
                Rule::unique(User::class, 'username')->ignore($userId),
            ],
        ];
    }
}
