<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'purpose',
        'channel',
        'recipient',
        'code',
        'expires_at',
        'verified_at',
        'attempts',
        'max_attempts',
    ];

    protected function casts(): array
    {
        return [
            'purpose' => OtpPurpose::class,
            'channel' => OtpChannel::class,
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'attempts' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function hasAttemptsLeft(): bool
    {
        return $this->attempts < $this->max_attempts;
    }

    public function scopeForRecipient(
        Builder $query,
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
    ): Builder {
        return $query
            ->where('purpose', $purpose->value)
            ->where('channel', $channel->value)
            ->where('recipient', $recipient);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('verified_at');
    }
}
