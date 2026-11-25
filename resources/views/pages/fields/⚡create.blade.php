<?php

use App\Models\Field;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $slug = '';

    public bool $show_btn = true;


    public function save(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'regex:/^[آ-یء\s\x{200C}]+$/u',
                'unique:fields,name',
            ],
            'slug' => [
                'required',
                'string',
                'min:2',
                'max:80',
                'regex:/\A[a-zA-Z]+(?:-[a-zA-Z]+)*\z/',
                'unique:fields,slug',
            ],
        ]);

        $field = Field::query()->create($validated);

        $this->modal('new-field')->close();

        $this->dispatch('field-created', id: $field->id);
    }
};
?>
<div>
    @if($show_btn)

        <flux:button
            type="button"
            variant="primary"
            color="blue"
            icon="plus"
            size="sm"
            class="cursor-pointer"
            x-on:click="$flux.modal('new-field').show()"
        >
            {{ __('رشته جدید') }}
        </flux:button>

    @endif
    <flux:modal name="new-field" :show="$errors->isNotEmpty()" focusable class="md:w-96" @close="reset_prop"
                :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('درج رشته جدید')}}</flux:heading>
                <flux:text class="mt-2">{{__('اطلاعات رشته آموزشی جدید را وارد کنید.')}}</flux:text>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 flex flex-col gap-3" autocomplete="off">

                <x-my.flt-lbl
                    name="name"
                    label="عنوان رشته"
                    type="text"
                    inputmode="text"
                    required
                    maxlength="60"
                    autofocus
                    class="font-semibold text-base!"
                />

                <x-my.flt-lbl
                    name="slug"
                    label="عنوان اسلاگ"
                    type="text"
                    inputmode="text"
                    dir="ltr"
                    required
                    maxlength="80"
                    class="font-semibold text-base!"
                />


                <div class="flex">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary" color="teal" size="sm"
                                 class="cursor-pointer">{{__('ثبت')}}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

