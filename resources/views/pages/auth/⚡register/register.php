<?php

use App\Contracts\SmsGateway;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Exceptions\Verification\SmsDeliveryException;
use App\Models\Mobile;
use App\Models\Person;
use App\Models\User;
use App\Services\OtpService;
use App\Support\Digits;
use App\Support\PasswordGenerator;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::auth')]
#[Title('ثبت نام')]
class extends Component
{
    public string $number = '';

    #[Locked]
    public ?string $verifiedNumber = null;

    public ?string $fingerprint = null;

    public bool $showOtpModal = false;

    public ?int $otpExpiresAt = null;

    public string $verificationCode = '';

    protected function rules(): array
    {
        return [
            'number' => ['bail', 'required', 'digits:11', 'starts_with:09'],
            'fingerprint' => ['required', 'string', 'regex:/^[a-f0-9]{32}$/'],
            'verificationCode' => ['required', 'digits:6'],
        ];
    }

    public function submit(OtpService $otpService): void
    {
        $this->number = Digits::toEnglish($this->number);

        $this->resetValidation();

        $this->validateOnly('number');
        $this->validateOnly('fingerprint');

        $mobileExists = Mobile::query()
            ->where('number', $this->number)
            ->whereHas('people')
            ->exists();

        if ($mobileExists) {
            $this->addError(
                'number',
                'این شماره موبایل قبلاً در سیستم ثبت شده است.',
            );

            return;
        }

        $result = $otpService->send(
            purpose: OtpPurpose::Registration,
            channel: OtpChannel::Sms,
            recipient: $this->number,
            ip: request()->ip(),
            fingerprint: $this->fingerprint,
        );

        match ($result['status']) {
            'sent' => $this->handleOtpSent($result),

            'active' => $this->handleActiveOtp($result),

            'recipient_limited' => $this->addError(
                'number',
                'برای این شماره اخیراً کد درخواست شده است. '
                .($result['seconds'] ?? 0)
                .' ثانیه دیگر تلاش کنید.',
            ),

            'ip_limited' => $this->addError(
                'number',
                'تعداد درخواست‌ها از این شبکه بیش از حد مجاز است. '
                .($result['seconds'] ?? 0)
                .' ثانیه دیگر تلاش کنید.',
            ),

            'fingerprint_limited' => $this->addError(
                'number',
                'تعداد درخواست‌ها از این دستگاه بیش از حد مجاز است. '
                .($result['seconds'] ?? 0)
                .' ثانیه دیگر تلاش کنید.',
            ),

            default => $this->addError(
                'number',
                'ارسال کد تأیید با مشکل مواجه شد. لطفاً دوباره تلاش کنید.',
            ),
        };
    }

    private function handleOtpSent(array $result): void
    {
        $this->otpExpiresAt = $this->resolveOtpTimestamp($result);

        Flux::toast(
            text: 'کد تأیید به شماره موبایل شما ارسال شد.',
            variant: 'success',
        );

        Flux::modal('verify-mobile')->show();
    }

    private function handleActiveOtp(array $result): void
    {
        $this->otpExpiresAt = $this->resolveOtpTimestamp($result);

        Flux::modal('verify-mobile')->show();
    }

    private function resolveOtpTimestamp(array $result): int
    {
        $expiresAt = $result['expires_at'] ?? null;

        if ($expiresAt instanceof DateTimeInterface) {
            return $expiresAt->getTimestamp();
        }

        if (is_numeric($expiresAt)) {
            return (int) $expiresAt;
        }

        if (is_string($expiresAt)) {
            return Carbon::parse($expiresAt)->timestamp;
        }

        return now()->addMinutes(2)->timestamp;
    }

    public function verifyOtp(OtpService $otpService): void
    {
        $this->verificationCode = Digits::toEnglish(
            $this->verificationCode,
        );

        $this->number = Digits::toEnglish($this->number);

        $this->resetValidation('verificationCode');

        $this->validateOnly('number');
        $this->validateOnly('fingerprint');
        $this->validateOnly('verificationCode');

        $result = $otpService->verify(
            purpose: OtpPurpose::Registration,
            channel: OtpChannel::Sms,
            recipient: $this->number,
            code: $this->verificationCode,
            ip: request()->ip(),
            fingerprint: $this->fingerprint,
        );

        match ($result['status']) {
            'verified' => $this->handleOtpVerified(),

            'invalid' => $this->addError(
                'verificationCode',
                'کد تأیید نامعتبر است.',
            ),

            'expired' => $this->addError(
                'verificationCode',
                'کد تأیید منقضی شده است یا هنوز درخواستی ثبت نکرده‌اید.',
            ),

            'rate_limited' => $this->addError(
                'verificationCode',
                'تعداد تلاش‌ها بیش از حد مجاز است. '
                .($result['seconds'] ?? 0)
                .' ثانیه دیگر تلاش کنید.',
            ),

            default => $this->addError(
                'verificationCode',
                'بررسی کد تأیید با مشکل مواجه شد. لطفاً دوباره تلاش کنید.',
            ),
        };
    }

    private function handleOtpVerified(): void
    {
        $this->verifiedNumber = $this->number;

        Flux::toast(
            text: 'شماره موبایل با موفقیت تأیید شد.',
            variant: 'success',
        );

        $this->register();
    }

    public function register(): void
    {
        $this->number = Digits::toEnglish($this->number);

        $this->validateOnly('number');

        if (
            $this->verifiedNumber === null
            || $this->verifiedNumber !== $this->number
        ) {
            $this->verifiedNumber = null;

            $this->addError(
                'number',
                'ابتدا شماره موبایل خود را تأیید کنید.',
            );

            return;
        }

        $mobile = Mobile::query()
            ->where('number', $this->verifiedNumber)
            ->whereHas('people')
            ->exists();

        if ($mobile) {
            $this->verifiedNumber = null;

            $this->addError(
                'number',
                'این شماره موبایل قبلاً در سیستم ثبت شده است.',
            );

            return;
        }

        $userExists = User::query()
            ->where('username', $this->verifiedNumber)
            ->exists();

        if ($userExists) {
            $this->verifiedNumber = null;

            $this->addError(
                'number',
                'برای این شماره موبایل قبلاً حساب کاربری ایجاد شده است.',
            );

            return;
        }

        $temporaryPassword = PasswordGenerator::generate();
        $user = DB::transaction(function () use ($temporaryPassword) {
            $person = Person::query()->create([]);

            $mobile = Mobile::query()->firstOrCreate([
                'number' => $this->verifiedNumber,
            ]);

            $mobile->people()->attach($person->id, [
                'is_primary' => true,
                'is_verified' => true,
            ]);

            return User::query()->create([
                'person_id' => $person->id,
                'username' => $this->verifiedNumber,
                'password' => $temporaryPassword,
                'password_is_temporary' => true,
                'is_active' => true,
            ]);
        });

        try {
            app(SmsGateway::class)->sendPassword(
                mobile: $this->verifiedNumber,
                username: $user->username,
                password: $temporaryPassword,
            );
        } catch (SmsDeliveryException $e) {
            report($e);

            $this->addError(
                'number',
                'حساب کاربری ساخته شد، اما ارسال پیامک رمز موقت ناموفق بود. لطفاً با پشتیبانی تماس بگیرید.',
            );

            return;
        }
        Auth::login($user);

        request()->session()->regenerate();

        $this->redirectIntended(
            default: route('dashboard'),
            navigate: true,
        );
    }
};
