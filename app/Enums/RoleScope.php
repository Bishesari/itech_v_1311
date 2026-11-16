<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleScope: string
{
    case System = 'system';
    case Institute = 'institute';
    case Branch = 'branch';
}
