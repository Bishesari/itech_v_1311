<div
    x-data="{ fingerprintReady: false }"
    x-init="
        window.getFingerprint().then(value => {
            $wire.fingerprint = value;
            fingerprintReady = true;
        })
    "
>
    <flux:card body="divided" variant="soft" size="xs" class="w-75">

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



    <flux:modal name="verify-mobile" class="mt-25 md:mt-78" :dismissible="false" @close="clearVerificationErrors()">
        <div
            x-data="{ remaining: 0, expiresAt: @js($otpExpiresAt), timer: null, expired: false,
            init() { this.startTimer();},
            startTimer() { this.stopTimer();  this.expired = false; this.updateTimer();
                if (this.remaining > 0) {
                    this.timer = setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                }
            },
            stopTimer() { clearInterval(this.timer); this.timer = null;},
            updateTimer() {
                this.remaining = Math.max(0, this.expiresAt - Math.floor(Date.now() / 1000));
                if (this.remaining === 0 && !this.expired) {
                    this.expired = true;
                    this.stopTimer();
                    $wire.closeExpiredOtpModal();
                }
            },
            formatTime() {
                const minutes = Math.floor(this.remaining / 60);
                const seconds = this.remaining % 60;
                return String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
                },
            }"
            x-effect="if (expiresAt !== @js($otpExpiresAt)) { expiresAt = @js($otpExpiresAt); startTimer();}"
            x-on:alpine:destroy="stopTimer()"
            class="space-y-6"
        >
            <flux:heading class="text-center">
                <span>{{__('تأیید شماره')}}</span>
                <span class="tracking-widest inline-block font-light">{{$number}}</span>
            </flux:heading>

            <form wire:submit="verifyOtp">
                <flux:otp wire:model="verificationCode" label="کد تأیید" label:sr-only
                          error:class="text-center" class="mx-auto font-instrument-sans" dir="ltr">
                    <flux:otp.group>
                        <flux:otp.input autofocus/>
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
                <flux:text class="text-center my-2">
                    <span x-show="remaining > 0">
                        <span>{{__('زمان باقی‌مانده:')}}</span>
                        <span dir="ltr" class="font-semibold tabular-nums font-mono" x-text="formatTime()"></span>
                    </span>
                    <span x-show="remaining <= 0">{{__('کد تأیید منقضی شده است.')}}</span>
                </flux:text>

                <flux:button type="submit" variant="filled" color="cyan" class="w-full cursor-pointer">{{__('تأیید کد')}}</flux:button>
            </form>

        </div>
    </flux:modal>



</div>

