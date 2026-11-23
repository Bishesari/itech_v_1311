<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleScope: string
{
    case System = 'system';
    case Institute = 'institute';
    case Branch = 'branch';

    public function label(): string
    {
        return match ($this) {
            self::System => 'سطح سیستم',
            self::Institute => 'سطح آموزشگاه',
            self::Branch => 'سطح شعبه',
        };
    }
}
