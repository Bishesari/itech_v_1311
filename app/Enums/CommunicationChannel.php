<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * کانال‌های ارتباطی قابل استفاده از طریق شماره موبایل.
 */

enum CommunicationChannel: string
{
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
    case Rubika = 'rubika';
    case Eitaa = 'eitaa';
    case Bale = 'bale';
}
