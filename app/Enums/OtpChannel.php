<?php

declare(strict_types=1);

namespace App\Enums;

enum OtpChannel: string
{
    case Sms = 'sms';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';

    public function label(): string
    {
        return match ($this) {
            self::Sms => 'پیامک',
            self::WhatsApp => 'واتساپ',
            self::Telegram => 'تلگرام',
        };
    }
}
