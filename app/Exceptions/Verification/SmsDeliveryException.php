<?php

declare(strict_types=1);

namespace App\Exceptions\Verification;

use Throwable;

final class SmsDeliveryException extends VerificationException
{
    public function __construct(
        string $message = 'ارسال پیامک با خطا مواجه شد.',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
