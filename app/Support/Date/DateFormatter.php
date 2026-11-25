<?php

namespace App\Support\Date;

use DateTimeInterface;

class DateFormatter
{
    public static function format(
        DateTimeInterface|string|null $date,
        string $format = 'Y/m/d H:i:s',
    ): string {
        if ($date === null) {
            return '—';
        }

        $timestamp = $date instanceof DateTimeInterface
            ? $date->getTimestamp()
            : strtotime($date);

        if ($timestamp === false) {
            return '—';
        }

        return jdate($format, $timestamp, '', 'local', 'en');
    }
}
