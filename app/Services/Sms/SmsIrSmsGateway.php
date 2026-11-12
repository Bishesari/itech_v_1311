<?php

declare(strict_types=1);

namespace App\Services\Sms;

use App\Contracts\SmsGateway;
use App\Exceptions\Verification\SmsDeliveryException;
use Illuminate\Support\Facades\Http;
use Throwable;

final class SmsIrSmsGateway implements SmsGateway
{
    public function sendOtp(
        string $mobile,
        string $verificationCode,
    ): void {
        $this->sendTemplate(
            mobile: $mobile,
            templateId: config('services.sms_ir.otp_template_id'),
            parameters: [
                [
                    'name' => config('services.sms_ir.otp_parameter_name', 'Code'),
                    'value' => $verificationCode,
                ],
            ],
            missingTemplateMessage: 'شناسه قالب پیامک OTP تنظیم نشده است.',
            failureMessage: 'ارسال پیامک با خطا مواجه شد.',
        );
    }

    public function sendPassword(
        string $mobile,
        string $username,
        string $password,
    ): void {
        $this->sendTemplate(
            mobile: $mobile,
            templateId: config('services.sms_ir.password_template_id'),
            parameters: [
                [
                    'name' => config(
                        'services.sms_ir.password_username_parameter_name',
                        'USERNAME',
                    ),
                    'value' => $username,
                ],
                [
                    'name' => config(
                        'services.sms_ir.password_parameter_name',
                        'PASSWORD',
                    ),
                    'value' => $password,
                ],
            ],
            missingTemplateMessage: 'شناسه قالب پیامک کلمه عبور تنظیم نشده است.',
            failureMessage: 'ارسال پیامک کلمه عبور با خطا مواجه شد.',
        );
    }

    /**
     * @param  array<int, array{name: string, value: string}>  $parameters
     */
    private function sendTemplate(
        string $mobile,
        mixed $templateId,
        array $parameters,
        string $missingTemplateMessage,
        string $failureMessage,
    ): void {
        if (! is_numeric($templateId) || (int) $templateId < 1) {
            throw new SmsDeliveryException($missingTemplateMessage);
        }

        $url = config('services.sms_ir.url');
        $apiKey = config('services.sms_ir.api_key');

        if (! is_string($url) || $url === '' || ! is_string($apiKey) || $apiKey === '') {
            throw new SmsDeliveryException(
                'تنظیمات اتصال به سرویس پیامک کامل نیست.',
            );
        }

        try {
            $response = Http::baseUrl($url)
                ->acceptJson()
                ->asJson()
                ->withHeaders([
                    'X-API-KEY' => $apiKey,
                ])
                ->connectTimeout(5)
                ->timeout(10)
                ->post('/send/verify', [
                    'mobile' => $mobile,
                    'templateId' => (int) $templateId,
                    'parameters' => $parameters,
                ]);

            $result = $response->json();

            if (
                ! $response->successful()
                || ! is_array($result)
                || ($result['status'] ?? null) !== 1
            ) {
                throw new SmsDeliveryException(
                    is_array($result) && is_string($result['message'] ?? null)
                        ? $result['message']
                        : $failureMessage,
                );
            }
        } catch (SmsDeliveryException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new SmsDeliveryException(
                'ارتباط با سرویس پیامک برقرار نشد.',
                previous: $e,
            );
        }
    }
}
