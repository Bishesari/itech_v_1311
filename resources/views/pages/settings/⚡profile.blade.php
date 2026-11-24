<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Support\Facades\Auth;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('تنظیمات حساب کاربری')] class extends Component {
    use ProfileValidationRules;

    public string $username = '';

    public function mount(): void
    {
        $this->username = Auth::user()->username;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate(
            $this->profileRules($user->id)
        );

        $user->update($validated);

        Flux::toast(
            variant: 'success',
            text: __('نام کاربری با موفقیت تغییر کرد.')
        );
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">
        {{ __('تنظیمات حساب کاربری') }}
    </flux:heading>

    <x-pages::settings.layout
        :heading="__('اطلاعات حساب کاربری')"
        :subheading="__('مشاهده اطلاعات هویتی و تغییر نام کاربری')"
    >
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            {{-- اطلاعات هویتی --}}
            <flux:input
                :label="__('نام و نام خانوادگی')"
                :value="Auth::user()->person->first_name_fa . ' ' . Auth::user()->person->last_name_fa"
                disabled
            />

            {{-- نام کاربری --}}
            <flux:input
                wire:model="username"
                :label="__('نام کاربری')"
                type="text"
                required
                autocomplete="username"
                maxlength="25"
            />

            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full"
                    wire:loading.attr="disabled"
                    wire:target="updateProfileInformation"
                >
                    {{ __('ذخیره تغییرات') }}
                </flux:button>
            </div>

        </form>
    </x-pages::settings.layout>
</section>
