<?php

declare(strict_types=1);

namespace App\Support;

final class PasswordGenerator
{
    public static function generate(): string
    {
        $digits = '123456789';
        $letters = 'abcdefghijkmnpqrstuvwxyz';

        return self::randomCharacters($digits, 2)
            .self::randomCharacters($letters, 2)
            .self::randomCharacters($digits, 2);
    }

    private static function randomCharacters(
        string $characters,
        int $length,
    ): string {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $result;
    }
}
