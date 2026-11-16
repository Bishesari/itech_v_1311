<x-layouts::auth :title="__('ورود')">

    <flux:card body="divided" variant="soft" size="xs" class="w-75">
        <flux:card.header class="text-center">
            <flux:card.heading class="font-bold! text-blue-500">{{__('فرم ورود')}}</flux:card.heading>
            <flux:card.subheading class="font-medium! text-xs!">{{__('برای ورود، نام کاربری و کلمه عبور خود را وارد کنید.')}}</flux:card.subheading>
            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />
        </flux:card.header>

        <form method="POST" action="{{ route('login.store') }}" autocomplete="off" x-data="{ loading: false }" @submit="loading = true">

            @csrf

            <flux:card.body class="space-y-6">
                <!-- User Name -->
                <x-my.flt-lbl name="username" label="{{ __('نام کاربری') }}" required dir="ltr" maxlength="25"
                              class="tracking-widest text-lg!" autofocus
                />

                <!-- Password -->
                <x-my.flt-lbl type="password" name="password" label="{{ __('کلمه عبور') }}" required dir="ltr" maxlength="30"
                              class="tracking-widest text-lg!"
                />

                <!-- Remember Me -->
                <div class="flex justify-between mx-2">
                    <flux:field variant="inline">
                        <flux:checkbox name="remember" class="cursor-pointer"/>
                        <flux:label class="cursor-pointer">{{__('بخاطرسپاری')}}</flux:label>
                    </flux:field>

                    <flux:link class="text-sm" :href="route('reset-password')" wire:navigate tabindex="-1"
                               x-data="{ loading: false }"
                               @click="loading = true"
                    >
                        <span x-show="!loading">{{ __('ریست کلمه عبور') }}</span>
                        <flux:icon.loading x-show="loading" class="inline size-4 text-yellow-500 ml-8"/>

                    </flux:link>
                </div>
            </flux:card.body>
            <flux:card.footer>

                <flux:button
                    type="submit"
                    variant="filled"
                    color="sky"
                    size="sm"
                    class="cursor-pointer"
                    data-test="login-button" x-bind:disabled="loading" :loading="false"
                >
                    <span x-show="!loading">{{ __('ورود') }}</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                                <span>{{ __('در حال پردازش  ... !') }}</span>
                                <flux:icon.loading class="size-5 animate-spin" />
                            </span>
                </flux:button>
                <flux:card.actions>
                    <flux:button :href="route('register')" wire:navigate variant="subtle" icon:trailing="arrow-right-end-on-rectangle"
                                 size="xs" class="cursor-pointer"
                                 x-data="{ loading: false }"
                                 @click="loading = true"
                    >
                        <span x-show="!loading">{{ __('ثبت نام') }}</span>
                        <flux:icon.loading x-show="loading" class="size-4 mx-1 text-amber-500"/>
                    </flux:button>
                </flux:card.actions>
            </flux:card.footer>
        </form>



    </flux:card>
</x-layouts::auth>
