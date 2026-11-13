<?php

use App\Contracts\SmsGateway;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Exceptions\Verification\SmsDeliveryException;
use App\Models\Mobile;
use App\Models\Person;
use App\Services\OtpService;
use App\Support\Digits;
use App\Support\PasswordGenerator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::auth')]
#[Title('بازیابی کلمه عبور')]
class extends Component
{
    public string $mobile = '';

    public ?string $fingerprint = null;

    #[Locked]
    public string $stage = 'mobile';

    public string $verificationCode = '';

    #[Locked]
    public ?int $otpExpiresAt = null;

    #[Locked]
    public array $accounts = [];

    public ?int $selectedPersonId = null;

    #[Locked]
    public bool $otpVerified = false;

    #[Locked]
    public ?string $verifiedMobile = null;

    #[Locked]
    public ?string $pendingMobile = null;

    #[Locked]
    public ?string $pendingFingerprint = null;

    protected function rules(): array
    {
        return [
            'mobile' => ['bail', 'required', 'digits:11', 'starts_with:09'],
            'fingerprint' => ['required', 'string', 'regex:/^[a-f0-9]{32}$/'],
            'verificationCode' => ['required', 'digits:6'],
        ];
    }

    public function submit(OtpService $otpService): void
    {
        if ($this->stage !== 'mobile') {
            return;
        }
        $this->mobile = Digits::toEnglish($this->mobile);

        $this->resetValidation();
        $this->verificationCode = '';
        $this->otpExpiresAt = null;
        $this->pendingMobile = null;
        $this->pendingFingerprint = null;
        $this->verifiedMobile = null;
        $this->otpVerified = false;
        $this->accounts = [];
        $this->selectedPersonId = null;

        $this->validateOnly('mobile');
        $this->validateOnly('fingerprint');

        $mobile = Mobile::query()
            ->where('number', $this->mobile)
            ->whereHas('people', fn ($query) => $query->wherePivot('is_verified', true))
            ->first();

        if ($mobile === null) {
            $this->stage = 'not-found';

            return;
        }
        try {
            $result = $otpService->send(
                OtpPurpose::PasswordReset,
                OtpChannel::Sms,
                $this->mobile,
                request()->ip(),
                $this->fingerprint,
            );
        } catch (SmsDeliveryException $e) {
            report($e);

            $this->pendingMobile = null;
            $this->pendingFingerprint = null;
            $this->addError(
                'mobile',
                'ارسال پیامک با خطا مواجه شد. لطفاً دوباره تلاش کنید.',
            );

            return;
        }

        match ($result['status']) {
            'sent', 'active' => $this->handleOtpSent($result),

            'recipient_limited', 'ip_limited', 'fingerprint_limited' => $this->addError(
                'mobile',
                'درخواست ارسال کد بیش از حد مجاز است. لطفاً کمی بعد دوباره تلاش کنید.',
            ),

            default => throw new LogicException('Unexpected OTP send status.'),
        };

    }

    private function handleOtpSent(array $result): void
    {
        $this->pendingMobile = $this->mobile;
        $this->otpExpiresAt = $result['expires_at'];
        $this->pendingFingerprint = $this->fingerprint;
        $this->stage = 'otp';

    }

    public function verifyOtp(OtpService $otpService): void
    {
        if (
            $this->stage !== 'otp'
            || $this->pendingMobile === null
            || $this->pendingFingerprint === null
        ) {
            return;
        }

        $this->verificationCode = Digits::toEnglish($this->verificationCode);
        $this->resetValidation('verificationCode');

        $this->validateOnly('verificationCode');

        $result = $otpService->verify(
            OtpPurpose::PasswordReset,
            OtpChannel::Sms,
            $this->pendingMobile,
            $this->verificationCode,
            request()->ip(),
            $this->pendingFingerprint,
        );

        match ($result['status']) {
            'verified' => $this->handleOtpVerified(),

            'invalid' => $this->addError(
                'verificationCode',
                'کد واردشده صحیح نیست.',
            ),

            'expired' => $this->handleOtpExpired(),

            'rate_limited' => $this->addError(
                'verificationCode',
                'تعداد تلاش‌های شما بیش از حد مجاز است. لطفاً کمی بعد دوباره تلاش کنید.',
            ),

            default => throw new LogicException('Unexpected OTP verify status.'),
        };
    }

    private function handleOtpExpired(): void
    {
        $this->resetValidation();

        $this->verificationCode = '';
        $this->otpExpiresAt = null;
        $this->pendingMobile = null;
        $this->pendingFingerprint = null;
        $this->verifiedMobile = null;
        $this->otpVerified = false;
        $this->accounts = [];
        $this->selectedPersonId = null;
        $this->stage = 'mobile';

        $this->addError(
            'mobile',
            'مهلت اعتبار کد تأیید به پایان رسیده است. لطفاً دوباره درخواست کد کنید.',
        );
    }

    private function handleOtpVerified(): void
    {
        $mobile = Mobile::query()
            ->where('number', $this->pendingMobile)
            ->whereHas('people', fn ($query) => $query->wherePivot('is_verified', true))
            ->with([
                'people' => fn ($query) => $query
                    ->wherePivot('is_verified', true)
                    ->whereHas('user')
                    ->with('user:id,person_id,username'),
            ])
            ->first();

        if ($mobile === null || $mobile->people->isEmpty()) {
            $this->otpVerified = false;
            $this->verifiedMobile = null;
            $this->pendingMobile = null;
            $this->pendingFingerprint = null;
            $this->verificationCode = '';
            $this->otpExpiresAt = null;
            $this->stage = 'support';

            return;
        }
        $this->otpVerified = true;

        $this->accounts = $mobile->people
            ->map(fn (Person $person) => [
                'person_id' => $person->id,
                'label' => $this->accountLabel($person),
            ])
            ->all();

        $this->verifiedMobile = $this->pendingMobile;
        $this->pendingMobile = null;
        $this->pendingFingerprint = null;
        $this->verificationCode = '';
        $this->otpExpiresAt = null;

        $this->selectedPersonId = null;

        if (count($this->accounts) === 1) {
            $this->selectedPersonId = $this->accounts[0]['person_id'];
            $this->stage = 'password';

            return;
        }

        $this->stage = 'choose-account';
    }

    private function accountLabel(Person $person): string
    {
        $name = trim(
            ($person->first_name_fa ?? '').' '.($person->last_name_fa ?? '')
        );

        if ($name !== '') {
            return $this->maskName($name);
        }

        $nickname = trim($person->nickname ?? '');

        if ($nickname !== '') {
            return $this->maskName($nickname);
        }

        return $this->maskUsername($person->user->username);
    }

    private function maskName(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY);

        return implode(' ', array_map(
            fn (string $part) => mb_substr($part, 0, 1).str_repeat('*', max(2, mb_strlen($part) - 1)),
            $parts,
        ));
    }

    private function maskUsername(string $username): string
    {
        $length = mb_strlen($username);

        if ($length <= 2) {
            return str_repeat('*', $length);
        }

        return mb_substr($username, 0, 2)
            .str_repeat('*', $length - 2);
    }

    public function selectAccount(): void
    {
        if ($this->stage !== 'choose-account' || ! $this->otpVerified) {
            return;
        }
        if ($this->accounts === []) {
            $this->stage = 'support';

            return;
        }
        $this->validate([
            'selectedPersonId' => [
                'required',
                'integer',
                'in:'.implode(',', array_column($this->accounts, 'person_id')),
            ],
        ]);

        $this->stage = 'password';
    }

    public function resetPassword(SmsGateway $smsGateway): void
    {
        if (
            $this->stage !== 'password'
            || ! $this->otpVerified
            || $this->verifiedMobile === null
            || $this->selectedPersonId === null
        ) {
            $this->backToMobile();

            return;
        }

        $person = Person::query()
            ->whereKey($this->selectedPersonId)
            ->whereHas('mobiles', fn ($query) => $query
                ->where('mobiles.number', $this->verifiedMobile)
                ->wherePivot('is_verified', true))
            ->with('user')
            ->first();

        if ($person === null || $person->user === null) {
            $this->otpVerified = false;
            $this->verifiedMobile = null;
            $this->selectedPersonId = null;
            $this->accounts = [];
            $this->stage = 'support';

            return;
        }

        $password = PasswordGenerator::generate();
        $this->resetValidation('mobile');

        try {
            $smsGateway->sendPassword(
                $this->verifiedMobile,
                $person->user->username,
                $password,
            );
        } catch (SmsDeliveryException $e) {
            report($e);

            $this->addError(
                'mobile',
                'ارسال پیامک با خطا مواجه شد. لطفاً دوباره تلاش کنید.',
            );

            return;
        }

        $person->user->update([
            'password' => $password,
            'password_is_temporary' => true,
        ]);

        $this->otpVerified = false;
        $this->verifiedMobile = null;
        $this->pendingMobile = null;
        $this->pendingFingerprint = null;
        $this->otpExpiresAt = null;
        $this->verificationCode = '';
        $this->selectedPersonId = null;
        $this->accounts = [];

        $this->stage = 'completed';
    }

    public function backToMobile(): void
    {
        $this->stage = 'mobile';
        $this->verificationCode = '';
        $this->otpExpiresAt = null;
        $this->pendingMobile = null;
        $this->pendingFingerprint = null;
        $this->verifiedMobile = null;
        $this->otpVerified = false;
        $this->accounts = [];
        $this->selectedPersonId = null;

        $this->resetValidation();
    }
};
