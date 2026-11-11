<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final class JalaliDate
{
    private const TIMEZONE = 'Asia/Tehran';

    public static function toGregorian(
        int $year,
        int $month,
        int $day,
    ): CarbonImmutable {
        if (! jcheckdate($month, $day, $year)) {
            throw new InvalidArgumentException('Invalid Jalali date.');
        }

        [$gy, $gm, $gd] = jalali_to_gregorian($year, $month, $day);

        return CarbonImmutable::create(
            $gy,
            $gm,
            $gd,
            0,
            0,
            0,
            self::TIMEZONE,
        );
    }

    public static function fromGregorian(
        CarbonImmutable $date,
    ): array {
        $date = $date->setTimezone(self::TIMEZONE);

        return gregorian_to_jalali(
            $date->year,
            $date->month,
            $date->day,
        );
    }
    public static function dayRange(
        int $year,
        int $month,
        int $day,
    ): array {
        $start = self::toGregorian($year, $month, $day);

        return [
            'start' => $start,
            'end' => $start->addDay(),
        ];
    }
    public static function weekRange(
        int $year,
        int $month,
        int $day,
    ): array {
        $date = self::toGregorian($year, $month, $day);

        // Carbon: Sunday = 0, Saturday = 6
        $daysSinceSaturday = ($date->dayOfWeek + 1) % 7;

        $start = $date->subDays($daysSinceSaturday);

        return [
            'start' => $start,
            'end' => $start->addWeek(),
        ];
    }
    public static function monthRange(
        int $year,
        int $month,
    ): array {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException('Invalid Jalali month.');
        }

        $start = self::toGregorian($year, $month, 1);

        if ($month === 12) {
            $nextYear = $year + 1;
            $nextMonth = 1;
        } else {
            $nextYear = $year;
            $nextMonth = $month + 1;
        }

        $end = self::toGregorian($nextYear, $nextMonth, 1);

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
    public static function yearRange(int $year): array
    {
        $start = self::toGregorian($year, 1, 1);
        $end = self::toGregorian($year + 1, 1, 1);

        return [
            'start' => $start,
            'end' => $end,
        ];
    }



}
