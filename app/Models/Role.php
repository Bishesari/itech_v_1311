<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleCode;
use App\Enums\RoleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'code',
        'name',
        'scope',
        'description',
        'color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'code' => RoleCode::class,
            'scope' => RoleScope::class,
            'is_active' => 'boolean',
        ];
    }

    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleAssignment::class);
    }
}
