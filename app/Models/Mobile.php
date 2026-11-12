<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mobile extends Model
{
    protected $fillable = [
        'number',
    ];

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'mobile_person',
            'mobile_id',
            'person_id'
        )
            ->using(MobilePerson::class)
            ->withPivot(['id', 'is_primary', 'is_verified'])
            ->withTimestamps();
    }
}
