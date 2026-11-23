<flux:card body="divided" variant="soft" size="xs" class="w-75">
    <flux:card.header class="text-center">
        <flux:card.heading class="font-bold! text-blue-500">{{__('انتخاب نقش کاربری')}}</flux:card.heading>
        <flux:card.subheading class="font-medium! text-xs!">{{__('برای ورود، یکی از نقش‌های زیر را انتخاب کنید.')}}</flux:card.subheading>
    </flux:card.header>
    <flux:card.body class="space-y-6">
        {{-- Roles --}}
        @forelse($this->assignments as $assignment)

            <flux:callout
                wire:click="select({{ $assignment->id }})"
                color="{{ $this->selectedAssignmentId == $assignment->id
                ? ($assignment->role->color ?? 'indigo')
                : 'zinc' }}"
                class="cursor-pointer transition"
                wire:loading.attr="disabled"
                wire:target="select"
            >

                <flux:callout.heading class="flex items-center justify-between">

                <span class="font-medium">
                    {{ $assignment->role->name }}
                </span>

                    @if($assignment->membership)
                        <span class="text-xs font-light">
                        {{ __('شعبه : ') }} {{ $assignment->membership->branch->short_name }}
                    </span>
                    @endif

                </flux:callout.heading>

            </flux:callout>

        @empty

            <p class="text-center text-gray-500 dark:text-gray-400">
                {{ __('شما هیچ نقش فعالی ندارید.') }}
            </p>

        @endforelse

        {{-- Validation --}}
        @error('selectedAssignmentId')
        <p class="text-center text-sm text-red-500">
            {{ $message }}
        </p>
        @enderror

    </flux:card.body>
    <flux:card.footer>
        {{-- Continue --}}
        @if($this->selectedAssignment)

            <flux:button
                wire:click="confirm"
                wire:loading.attr="disabled"
                wire:target="confirm"
                variant="primary"
                color="{{ $this->selectedAssignment->role->color ?? 'indigo' }}"
                class="relative w-full cursor-pointer py-2 text-sm font-medium"
            >
                <span>{{ __('ادامه با نقش انتخابی') }}</span>
            </flux:button>

        @endif

    </flux:card.footer>

</flux:card>

