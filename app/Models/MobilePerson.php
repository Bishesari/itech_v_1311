<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table(incrementing: true)]
class MobilePerson extends Pivot
{
    protected $fillable = [
        'person_id',
        'mobile_id',
        'is_primary',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function channels(): HasMany
    {
        return $this->hasMany(MobilePersonChannel::class);
    }
}
