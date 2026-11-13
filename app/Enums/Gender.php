<?php

declare(strict_types=1);

namespace App\Enums;

enum Gender: int
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'مرد',
            self::FEMALE => 'زن',
            self::OTHER => 'سایر',
        };
    }
}
