<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CommunicationChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobilePersonChannel extends Model
{
    protected $fillable = [
        'mobile_person_id',
        'channel',
    ];

    protected function casts(): array
    {
        return [
            'channel' => CommunicationChannel::class,
        ];
    }

    public function mobilePerson(): BelongsTo
    {
        return $this->belongsTo(MobilePerson::class);
    }
}
