<div class="w-85">
    <flux:card variant="soft" size="sm">
        <flux:card.header>
            <flux:card.heading class="text-center">{{__('فرم ثبت نام')}}</flux:card.heading>
            <flux:card.subheading class="text-center">{{__('برای شروع، شماره موبایل خود را وارد کنید.')}}</flux:card.subheading>
        </flux:card.header>
    </flux:card>
    <form wire:submit="save" autocomplete="off">
        <flux:card.body class="space-y-6">
            {{-- موبایل --}}
            <x-my.flt-lbl name="mobile" label="{{ __('شماره موبایل') }}" required dir="ltr"
                          :attributes="$attributes->merge(['maxlength' => 11, 'inputmode' => 'numeric'])"
                          class="tracking-widest text-base! font-instrument-sans" autofocus
            />

        </flux:card.body>
    </form>
</div>
