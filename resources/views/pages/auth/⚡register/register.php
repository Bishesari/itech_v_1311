<?php

use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Models\Mobile;
use App\Services\OtpService;
use App\Support\Digits;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::auth')] #[Title('ثبت نام')] class extends Component
{
    public string $number = '';
    public ?string $fingerprint = null;

    protected function rules(): array
    {
        return [
            'number' => ['bail', 'required', 'digits:11', 'starts_with:09'],
            'fingerprint' => ['required', 'string', 'regex:/^[a-f0-9]{32}$/'],
        ];
    }

    public function submit(OtpService $otpService): void
    {
        $this->number = Digits::toEnglish($this->number);

        $this->validate();

        if (Mobile::query()
            ->where('number', $this->number)
            ->whereHas('people')
            ->exists()) {
            $this->addError('number', 'این شماره موبایل قبلاً در سیستم ثبت شده است.');

            return;
        }

        $result = $otpService->send(
            purpose: OtpPurpose::Registration,
            channel: OtpChannel::Sms,
            recipient: $this->number,
            ip: request()->ip(),
            fingerprint: $this->fingerprint,
        );
    }
};
