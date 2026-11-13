<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    public function mobiles(): BelongsToMany
    {
        return $this->belongsToMany(
            Mobile::class,
            'mobile_person',
            'person_id',
            'mobile_id'
        )
            ->using(MobilePerson::class)
            ->withPivot(['id', 'is_primary', 'is_verified'])
            ->withTimestamps();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
