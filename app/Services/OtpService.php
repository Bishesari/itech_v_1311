<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SmsGateway;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Exceptions\Verification\SmsDeliveryException;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use InvalidArgumentException;

final class OtpService
{
    private const int OTP_TTL_MINUTES = 2;
    private const int SEND_LIMIT_PER_MOBILE = 1;
    private const int SEND_LIMIT_PER_IP = 30;
    private const int SEND_LIMIT_PER_FINGERPRINT = 3;
    private const int SEND_DECAY_SECONDS = 120;
    private const int VERIFY_LIMIT_PER_RECIPIENT = 5;
    private const int VERIFY_LIMIT_PER_IP = 60;
    private const int VERIFY_LIMIT_PER_FINGERPRINT = 10;
    private const int VERIFY_DECAY_SECONDS = 300;
    private const int MAX_ATTEMPTS_PER_OTP = 5;
    public function __construct(
        private readonly SmsGateway $smsGateway,
    ) {}

    /**
     * درخواست OTP برای یک کاربرد، کانال و مقصد مشخص.
     *
     * status:
     * - active
     * - sent
     * - recipient_limited
     * - ip_limited
     */

    public function send(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
        string $ip,
        string $fingerprint,
    ): array {
        $this->validateChannel($channel);

        $activeOtp = $this->activeOtp(
            $purpose,
            $channel,
            $recipient,
        );

        if ($activeOtp !== null) {
            return [
                'status' => 'active',
                'expires_at' => $activeOtp->expires_at->timestamp,
            ];
        }

        $recipientKey = $this->recipientRateLimitKey(
            $purpose,
            $channel,
            $recipient,
        );

        $ipKey = $this->ipRateLimitKey(
            $purpose,
            $channel,
            $ip,
        );

        $fingerprintKey = $this->sendFingerprintRateLimitKey(
            $purpose,
            $channel,
            $fingerprint,
        );

        if (RateLimiter::tooManyAttempts(
            $recipientKey,
            self::SEND_LIMIT_PER_MOBILE,
        )) {
            return [
                'status' => 'recipient_limited',
                'seconds' => RateLimiter::availableIn($recipientKey),
            ];
        }

        if (RateLimiter::tooManyAttempts(
            $ipKey,
            self::SEND_LIMIT_PER_IP,
        )) {
            return [
                'status' => 'ip_limited',
                'seconds' => RateLimiter::availableIn($ipKey),
            ];
        }

        if (RateLimiter::tooManyAttempts(
            $fingerprintKey,
            self::SEND_LIMIT_PER_FINGERPRINT,
        )) {
            return [
                'status' => 'fingerprint_limited',
                'seconds' => RateLimiter::availableIn($fingerprintKey),
            ];
        }

        RateLimiter::hit(
            $recipientKey,
            self::SEND_DECAY_SECONDS,
        );

        RateLimiter::hit(
            $ipKey,
            self::SEND_DECAY_SECONDS,
        );

        RateLimiter::hit(
            $fingerprintKey,
            self::SEND_DECAY_SECONDS,
        );

        $code = (string) random_int(100000, 999999);

        $otp = OtpVerification::query()->create([
            'purpose' => $purpose,
            'channel' => $channel,
            'recipient' => $recipient,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'attempts' => 0,
            'max_attempts' => self::MAX_ATTEMPTS_PER_OTP,
        ]);

        try {
            $this->sendCode(
                $channel,
                $recipient,
                $code,
            );
        } catch (SmsDeliveryException $e) {
            $otp->delete();

            throw $e;
        }

        return [
            'status' => 'sent',
            'expires_at' => $otp->expires_at->timestamp,
        ];
    }


    /**
     * بررسی OTP و ثبت زمان تأیید پس از موفقیت.
     *
     * status:
     * - verified
     * - invalid
     * - expired
     * - rate_limited
     */

    public function verify(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
        string $code,
        string $ip,
        string $fingerprint,
    ): array {
        $this->validateChannel($channel);

        $otp = OtpVerification::query()
            ->forRecipient($purpose, $channel, $recipient)
            ->pending()
            ->latest('id')
            ->first();

        if ($otp === null || $otp->isExpired()) {
            return ['status' => 'expired'];
        }

        $recipientKey = $this->verifyRecipientRateLimitKey(
            $purpose,
            $channel,
            $recipient,
        );

        $ipKey = $this->verifyIpRateLimitKey(
            $purpose,
            $channel,
            $ip,
        );

        $fingerprintKey = $this->verifyFingerprintRateLimitKey(
            $purpose,
            $channel,
            $fingerprint,
        );

        if (RateLimiter::tooManyAttempts(
            $recipientKey,
            self::VERIFY_LIMIT_PER_RECIPIENT,
        )) {
            return [
                'status' => 'rate_limited',
                'seconds' => RateLimiter::availableIn($recipientKey),
            ];
        }

        if (RateLimiter::tooManyAttempts(
            $ipKey,
            self::VERIFY_LIMIT_PER_IP,
        )) {
            return [
                'status' => 'rate_limited',
                'seconds' => RateLimiter::availableIn($ipKey),
            ];
        }

        if (RateLimiter::tooManyAttempts(
            $fingerprintKey,
            self::VERIFY_LIMIT_PER_FINGERPRINT,
        )) {
            return [
                'status' => 'rate_limited',
                'seconds' => RateLimiter::availableIn($fingerprintKey),
            ];
        }

        if (! $otp->hasAttemptsLeft()) {
            return [
                'status' => 'rate_limited',
                'seconds' => max(
                    0,
                    now()->diffInSeconds($otp->expires_at, false),
                ),
            ];
        }

        if (! Hash::check($code, $otp->code)) {
            $otp->increment('attempts');

            RateLimiter::hit(
                $recipientKey,
                self::VERIFY_DECAY_SECONDS,
            );

            RateLimiter::hit(
                $ipKey,
                self::VERIFY_DECAY_SECONDS,
            );

            RateLimiter::hit(
                $fingerprintKey,
                self::VERIFY_DECAY_SECONDS,
            );

            return ['status' => 'invalid'];
        }

        $otp->forceFill([
            'verified_at' => now(),
        ])->save();

        return ['status' => 'verified'];
    }


    /**
     * OTPهای بازنشدهٔ قبلی را برای این مقصد منقضی می‌کند.
     *
     * سوابق حذف نمی‌شوند.
     */
    public function expirePending(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
    ): int {
        return OtpVerification::query()
            ->forRecipient($purpose, $channel, $recipient)
            ->pending()
            ->where('expires_at', '>', now())
            ->update([
                'expires_at' => now(),
            ]);
    }

    private function activeOtp(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
    ): ?OtpVerification {
        return OtpVerification::query()
            ->forRecipient($purpose, $channel, $recipient)
            ->pending()
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();
    }

    private function sendCode(
        OtpChannel $channel,
        string $recipient,
        string $code,
    ): void {
        match ($channel) {
            OtpChannel::Sms => $this->smsGateway->sendOtp(
                $recipient,
                $code,
            ),

            default => throw new InvalidArgumentException(
                "OTP channel [{$channel->value}] is not supported yet.",
            ),
        };
    }

    private function verifyRecipientRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
    ): string {
        return implode(':', [
            'verify-otp',
            $purpose->value,
            $channel->value,
            'recipient',
            $recipient,
        ]);
    }

    private function verifyIpRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $ip,
    ): string {
        return implode(':', [
            'verify-otp',
            $purpose->value,
            $channel->value,
            'ip',
            $ip,
        ]);
    }

    private function validateChannel(OtpChannel $channel): void
    {
        if ($channel !== OtpChannel::Sms) {
            throw new InvalidArgumentException(
                "OTP channel [{$channel->value}] is not supported yet.",
            );
        }
    }

    private function recipientRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $recipient,
    ): string {
        return implode(':', [
            'send-otp',
            $purpose->value,
            $channel->value,
            'recipient',
            $recipient,
        ]);
    }

    private function ipRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $ip,
    ): string {
        return implode(':', [
            'send-otp',
            $purpose->value,
            $channel->value,
            'ip',
            $ip,
        ]);
    }

    private function sendFingerprintRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $fingerprint,
    ): string {
        return implode(':', [
            'send-otp',
            $purpose->value,
            $channel->value,
            'fingerprint',
            hash_hmac('sha256', $fingerprint, config('app.key')),
        ]);
    }

    private function verifyFingerprintRateLimitKey(
        OtpPurpose $purpose,
        OtpChannel $channel,
        string $fingerprint,
    ): string {
        return implode(':', [
            'verify-otp',
            $purpose->value,
            $channel->value,
            'fingerprint',
            hash_hmac('sha256', $fingerprint, config('app.key')),
        ]);
    }
}
