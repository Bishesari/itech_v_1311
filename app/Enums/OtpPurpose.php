<?php

declare(strict_types=1);

namespace App\Enums;

enum OtpPurpose: string
{
    case Registration = 'registration';
    case PasswordReset = 'password-reset';

    public function label(): string
    {
        return match ($this) {
            self::Registration => 'ثبت‌نام',
            self::PasswordReset => 'بازنشانی رمز عبور',
        };
    }
}
