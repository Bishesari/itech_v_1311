
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
            <flux:card.heading class="font-bold! text-amber-600 dark:text-amber-200">
                {{ __('بازیابی رمز عبور') }}
            </flux:card.heading>

            @if ($stage === 'mobile')
                <flux:card.subheading class="font-medium! text-xs!">
                    {{ __('شماره موبایل خود را وارد کنید.') }}
                </flux:card.subheading>
            @elseif ($stage === 'otp')
                <flux:card.subheading class="font-medium! text-xs!">
                    {{ __('دریافت کد پیامک شده به ') }}
                    <span dir="ltr">{{ $pendingMobile }}</span>
                </flux:card.subheading>
            @elseif ($stage === 'choose-account')
                <flux:card.subheading class="font-medium! text-xs!">
                    {{ __('انتخاب حساب کاربری') }}
                </flux:card.subheading>
            @endif
        </flux:card.header>

        @if ($stage === 'mobile')
            <form wire:submit="submit" autocomplete="off">
                <flux:card.body>
                    <x-my.flt-lbl
                        name="mobile"
                        label="{{ __('شماره موبایل') }}"
                        :required="true"
                        dir="ltr"
                        maxlength="11"
                        inputmode="numeric"
                        class="tracking-widest"
                        autofocus
                    />

                    <div x-show="!fingerprintReady && !fingerprintError"
                         class="text-center text-xs text-zinc-500">
                        {{ __('در حال آماده‌سازی...') }}
                    </div>

                    <div x-show="fingerprintError"
                         class="text-center text-xs text-red-500">
                        {{ __('آماده‌سازی اطلاعات مرورگر با خطا مواجه شد. صفحه را دوباره بارگذاری کنید.') }}
                    </div>
                </flux:card.body>

                <flux:card.footer>
                    <flux:button
                        type="submit"
                        variant="filled"
                        color="yellow"
                        size="sm"
                        class="cursor-pointer"
                        x-bind:disabled="!fingerprintReady"
                    >
                        {{ __('ادامه') }}
                    </flux:button>
                </flux:card.footer>
            </form>

        @elseif ($stage === 'otp')
            <form
                wire:submit="verifyOtp"
                wire:key="password-reset-otp-{{ $otpExpiresAt }}"
                x-init="$nextTick(() => $el.querySelector('input')?.focus())"
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
            >
                <flux:card.body>
                    <div x-show="remaining > 0">
                        <flux:otp
                            wire:model="verificationCode"
                            label=""
                            label:sr-only
                            required
                            error:class="text-center"
                            class="mx-auto font-instrument-sans"
                            dir="ltr"
                        >
                            <flux:otp.group>
                                <flux:otp.input />
                                <flux:otp.input />
                                <flux:otp.input />
                            </flux:otp.group>

                            <flux:otp.separator />

                            <flux:otp.group>
                                <flux:otp.input />
                                <flux:otp.input />
                                <flux:otp.input />
                            </flux:otp.group>
                        </flux:otp>

                        <flux:text class="text-center mt-2">
                            <span>{{ __('زمان باقی‌مانده:') }}</span>
                            <span
                                dir="ltr"
                                class="font-semibold tabular-nums font-mono"
                                x-text="formattedTime"
                            ></span>
                        </flux:text>
                    </div>

                    <flux:text
                        class="text-center text-amber-500"
                        x-show="remaining <= 0"
                    >
                        {{ __('کد تأیید منقضی شده است. لطفا دوباره درخواست کنید.') }}
                    </flux:text>
                </flux:card.body>

                <flux:card.footer>
                    <flux:button
                        type="submit"
                        variant="filled"
                        color="blue"
                        size="sm"
                        class="cursor-pointer"
                        x-show="remaining > 0"
                    >
                        {{ __('تأیید کد') }}
                    </flux:button>

                    <flux:button
                        wire:click="backToMobile"
                        variant="filled"
                        color="yellow"
                        size="sm"
                        class="cursor-pointer"
                        wire:loading.attr="disabled"
                        wire:target="backToMobile"
                        x-show="remaining === 0"
                    >
                        {{ __('درخواست کد جدید') }}
                    </flux:button>
                </flux:card.footer>
            </form>

        @elseif ($stage === 'choose-account')
            <form wire:submit="selectAccount" class="space-y-4">
                <flux:card.body>
                    <flux:text size="sm">
                        چند حساب با این شماره موبایل مرتبط است. حساب موردنظر خود را انتخاب کنید.
                    </flux:text>

                    @if (count($accounts) > 1)
                        <flux:radio.group wire:model="selectedPersonId">
                            @foreach ($accounts as $account)
                                <flux:radio
                                    value="{{ $account['person_id'] }}"
                                    label="{{ $account['label'] }}"
                                />
                            @endforeach
                        </flux:radio.group>
                    @endif

                    @error('selectedPersonId')
                    <flux:text color="red" size="sm">
                        {{ $message }}
                    </flux:text>
                    @enderror

                    @error('mobile')
                    <flux:text color="red" size="sm">
                        {{ $message }}
                    </flux:text>
                    @enderror
                </flux:card.body>

                <flux:card.footer>
                    <flux:button
                        type="submit"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="selectAccount"
                    >
                        <span wire:loading.remove wire:target="selectAccount">
                            {{ __('ادامه') }}
                        </span>

                        <span wire:loading wire:target="selectAccount">
                            {{ __('در حال ارسال...') }}
                        </span>
                    </flux:button>
                </flux:card.footer>
            </form>

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
    </flux:card>
</div>
