
<div
    x-data="{
        fingerprintReady: false,
        fingerprintError: false,

        async init() {
            try {
                const value = await window.getFingerprint();

                if (!value || !/^[a-f0-9]{32}$/.test(value)) {
                    throw new Error('Invalid fingerprint');
                }

                await $wire.$set('fingerprint', value);
                this.fingerprintReady = true;
            } catch (error) {
                this.fingerprintError = true;
                console.error('Fingerprint initialization failed:', error);
            }
        }
    }"
>
    <flux:card body="divided" variant="soft" size="xs" class="mx-auto w-80">
        <flux:card.header class="text-center">
            <flux:card.heading class="font-bold">
                بازیابی رمز عبور
            </flux:card.heading>

            <div class="text-sm text-zinc-500">
                برای بازیابی رمز عبور، شماره موبایل خود را وارد کنید.
            </div>
        </flux:card.header>

        <flux:card.body>
            @if ($stage === 'mobile')
                <form wire:submit="submit" class="space-y-4">
                    <flux:input
                        wire:model="mobile"
                        label="شماره موبایل"
                        placeholder="09123456789"
                        inputmode="numeric"
                        dir="ltr"
                        autofocus
                    />

                    @error('mobile')
                    <flux:text color="danger" size="sm">{{ $message }}</flux:text>
                    @enderror

                    <div
                        x-show="!fingerprintReady && !fingerprintError"
                        class="text-center text-xs text-zinc-500"
                    >
                        در حال آماده‌سازی...
                    </div>

                    <div
                        x-show="fingerprintError"
                        class="text-center text-xs text-red-500"
                    >
                        آماده‌سازی اطلاعات مرورگر با خطا مواجه شد. صفحه را دوباره بارگذاری کنید.
                    </div>

                    <flux:button
                        type="submit"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        x-bind:disabled="!fingerprintReady"
                    >
                        <span wire:loading.remove wire:target="submit">ادامه</span>
                        <span wire:loading wire:target="submit">در حال ارسال...</span>
                    </flux:button>
                </form>

            @elseif ($stage === 'otp')
                <div
                    wire:key="password-reset-otp-{{ $otpExpiresAt }}"
                    x-data="{
                        remaining: Math.max(0, {{ $otpExpiresAt }} - Math.floor(Date.now() / 1000)),
                        timer: null,

                        init() {
                            this.timer = setInterval(() => {
                                this.remaining = Math.max(0, this.remaining - 1);

                                if (this.remaining === 0) {
                                    clearInterval(this.timer);
                                    this.timer = null;
                                }
                            }, 1000);
                        },

                        destroy() {
                            clearInterval(this.timer);
                        },

                        get formattedTime() {
                            const minutes = Math.floor(this.remaining / 60);
                            const seconds = this.remaining % 60;

                            return `${minutes}:${String(seconds).padStart(2, '0')}`;
                        }
                    }"
                    class="space-y-4"
                >
                    <flux:text size="sm" class="text-center">
                        کد تأیید به شماره
                        <span dir="ltr" class="font-semibold">{{ $pendingMobile }}</span>
                        ارسال شد.
                    </flux:text>

                    <div class="text-center text-sm text-zinc-500">
                        <span x-show="remaining > 0">
                            اعتبار کد:
                            <span
                                x-text="formattedTime"
                                dir="ltr"
                                class="font-semibold tabular-nums"
                            ></span>
                        </span>

                        <div x-show="remaining === 0" class="space-y-2">
                            <div class="text-amber-600">
                                اعتبار کد به پایان رسیده است. لطفاً دوباره درخواست کد کنید.
                            </div>

                            <flux:button
                                wire:click="backToMobile"
                                variant="ghost"
                                size="sm"
                                wire:loading.attr="disabled"
                                wire:target="backToMobile"
                            >
                                درخواست کد جدید
                            </flux:button>
                        </div>
                    </div>

                    <form
                        wire:submit="verifyOtp"
                        class="space-y-4"
                        x-show="remaining > 0"
                    >
                        <flux:input
                            wire:model="verificationCode"
                            label="کد تأیید پیامک‌شده"
                            placeholder="123456"
                            inputmode="numeric"
                            dir="ltr"
                            maxlength="6"
                            autofocus
                        />

                        @error('verificationCode')
                        <flux:text color="danger" size="sm">{{ $message }}</flux:text>
                        @enderror

                        <flux:button
                            type="submit"
                            variant="primary"
                            class="w-full"
                            wire:loading.attr="disabled"
                            wire:target="verifyOtp"
                            x-bind:disabled="remaining === 0"
                        >
                            <span wire:loading.remove wire:target="verifyOtp">تأیید کد</span>
                            <span wire:loading wire:target="verifyOtp">در حال بررسی...</span>
                        </flux:button>
                    </form>
                </div>

            @elseif ($stage === 'choose-account')
                <form wire:submit="selectAccount" class="space-y-4">
                    <flux:text size="sm">
                        چند حساب با این شماره موبایل مرتبط است. حساب موردنظر خود را انتخاب کنید.
                    </flux:text>

                    <flux:radio.group wire:model="selectedPersonId">
                        @foreach ($accounts as $account)
                            <flux:radio
                                value="{{ $account['person_id'] }}"
                                label="{{ $account['label'] }}"
                            />
                        @endforeach
                    </flux:radio.group>

                    @error('selectedPersonId')
                    <flux:text color="danger" size="sm">{{ $message }}</flux:text>
                    @enderror

                    <flux:button
                        type="submit"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="selectAccount"
                    >
                        <span wire:loading.remove wire:target="selectAccount">ادامه</span>
                        <span wire:loading wire:target="selectAccount">در حال بررسی...</span>
                    </flux:button>
                </form>

            @elseif ($stage === 'password')
                <div class="space-y-4">
                    <flux:text size="sm">
                        با انتخاب ادامه، یک رمز عبور موقت به شماره موبایل تأییدشده شما ارسال خواهد شد.
                    </flux:text>

                    <flux:button
                        wire:click="resetPassword"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="resetPassword"
                    >
                        <span wire:loading.remove wire:target="resetPassword">
                            ارسال رمز عبور موقت
                        </span>

                        <span wire:loading wire:target="resetPassword">
                            در حال ارسال...
                        </span>
                    </flux:button>

                    @error('mobile')
                    <flux:text color="danger" size="sm">{{ $message }}</flux:text>
                    @enderror
                </div>

            @elseif ($stage === 'completed')
                <div class="space-y-4 text-center">
                    <flux:icon
                        name="check-circle"
                        variant="solid"
                        class="mx-auto size-12 text-green-500"
                    />

                    <flux:heading size="lg">
                        رمز عبور با موفقیت ارسال شد
                    </flux:heading>

                    <flux:text size="sm">
                        رمز عبور موقت به شماره موبایل شما پیامک شد.
                    </flux:text>
                </div>

            @elseif ($stage === 'not-found')
                <div class="space-y-4 text-center">
                    <flux:icon
                        name="exclamation-circle"
                        class="mx-auto size-12 text-amber-500"
                    />

                    <flux:heading size="lg">
                        حسابی پیدا نشد
                    </flux:heading>

                    <flux:text size="sm">
                        حساب کاربری مرتبط با این شماره موبایل پیدا نشد.
                    </flux:text>

                    <flux:button
                        wire:click="backToMobile"
                        variant="primary"
                        class="w-full"
                    >
                        تلاش مجدد
                    </flux:button>
                </div>

            @elseif ($stage === 'support')
                <div class="space-y-4 text-center">
                    <flux:icon
                        name="lifebuoy"
                        class="mx-auto size-12 text-amber-500"
                    />

                    <flux:heading size="lg">
                        نیاز به پشتیبانی
                    </flux:heading>

                    <flux:text size="sm">
                        امکان ادامه فرایند بازیابی رمز عبور وجود ندارد. لطفاً با پشتیبانی تماس بگیرید.
                    </flux:text>
                </div>
            @endif
        </flux:card.body>
    </flux:card>
</div>
