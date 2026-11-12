<div
    x-data="{ fingerprintReady: false }"
    x-init="
        window.getFingerprint().then(value => {
            $wire.fingerprint = value;
            fingerprintReady = true;
        })
    "
>
    <flux:card body="divided" variant="soft" size="xs" class="w-80">

        <flux:card.header class="text-center">
            <flux:card.heading class="font-bold!">{{__('فرم ثبت نام')}}</flux:card.heading>
            <flux:card.subheading class="font-medium! text-xs!">{{__('برای شروع، شماره موبایل خود را وارد کنید.')}}</flux:card.subheading>
        </flux:card.header>


        <form wire:submit="submit" autocomplete="off">
            <flux:card.body class="space-y-6">
                <x-my.flt-lbl
                    name="number"
                    label="{{ __('شماره موبایل') }}"
                    :required="true"
                    dir="ltr"
                    maxlength="11"
                    inputmode="numeric"
                    class="tracking-widest"
                    autofocus
                />
            </flux:card.body>
            <flux:card.footer>

                <flux:button
                    type="submit"
                    variant="filled"
                    color="sky"
                    size="sm"
                    class="cursor-pointer"
                    x-bind:disabled="!fingerprintReady"
                >
                    {{ __('ثبت نام') }}
                </flux:button>
                <flux:card.actions>
                    <flux:button :href="route('login')" wire:navigate variant="subtle" icon:trailing="arrow-left-end-on-rectangle"
                                 size="xs" class="cursor-pointer"
                                 x-data="{ loading: false }"
                                 @click="loading = true"
                    >
                        <span x-show="!loading">{{ __('ورود') }}</span>
                        <flux:icon.loading x-show="loading" class="size-4 mx-1 text-amber-500"/>
                    </flux:button>
                </flux:card.actions>
            </flux:card.footer>
        </form>
    </flux:card>



    <flux:modal name="verify-mobile" class="md:w-80">
        <div
            x-data="{
            remaining: 0,
            expiresAt: @js($otpExpiresAt),
            timer: null,

            init() {
                this.updateTimer();

                this.timer = setInterval(() => {
                    this.updateTimer();
                }, 1000);
            },

            updateTimer() {
                this.remaining = Math.max(
                    0,
                    this.expiresAt - Math.floor(Date.now() / 1000)
                );
            },

            formatTime() {
                const minutes = Math.floor(this.remaining / 60);
                const seconds = this.remaining % 60;

                return String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
            },
        }"
            x-effect="
            if (expiresAt !== @js($otpExpiresAt)) {
                expiresAt = @js($otpExpiresAt);
                updateTimer();
            }
        "
            x-on:alpine:destroy="clearInterval(timer)"
            class="space-y-6"
        >
            <div>
                <flux:heading size="lg">{{__('تأیید شماره موبایل')}}</flux:heading>
                <flux:text class="mt-2">{{__('کد تأیید ارسال‌شده به شماره موبایل خود را وارد کنید.')}}</flux:text>
            </div>

            <flux:otp wire:model="verificationCode" label="کد تأیید" label:sr-only :error:icon="false" inputmode="numeric"
                      error:class="text-center" class="mx-auto" dir="ltr">
                <flux:otp.input autofocus/>
                <flux:otp.input />
                <flux:otp.input />
                <flux:otp.separator />
                <flux:otp.input />
                <flux:otp.input />
                <flux:otp.input />
            </flux:otp>
            @error('verificationCode')
            <flux:text color="red" size="sm">
                {{ $message }}
            </flux:text>
            @enderror

            <flux:text class="font-medium text-center" dir="ltr">
                {{ $number }}
            </flux:text>
            <flux:text class="text-center" aria-live="polite">
                <span x-show="remaining > 0">
        زمان باقی‌مانده:
        <span
            dir="ltr"
            class="font-semibold tabular-nums font-mono"
            x-text="formatTime()"
        ></span>
    </span>

                    <span x-show="remaining <= 0">
        کد تأیید منقضی شده است.
    </span>
                </flux:text>


                <flux:button
                    wire:click="verifyOtp"
                    variant="filled"
                    color="sky"
                    class="w-full cursor-pointer"
                >
                    تأیید کد
                </flux:button>
            </div>

    </flux:modal>



</div>

