<div>
    <flux:card body="divided" variant="soft" size="xs" class="w-80">

        <flux:card.header class="text-center">
            <flux:card.heading class="font-bold!">{{__('فرم ثبت نام')}}</flux:card.heading>
            <flux:card.subheading class="font-medium! text-xs!">{{__('برای شروع، شماره موبایل خود را وارد کنید.')}}</flux:card.subheading>
        </flux:card.header>


        <form wire:submit="submit" autocomplete="off">
            <flux:card.body class="space-y-6">
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
            </flux:card.body>
            <flux:card.footer>

                <flux:button type="submit" variant="filled" color="sky" size="sm" class="cursor-pointer">{{__('ثبت نام')}}</flux:button>
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
</div>

