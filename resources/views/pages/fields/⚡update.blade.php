<?php

use App\Models\Field;
use Livewire\Component;

new class extends Component {
    public Field $field;

    public string $name = '';

    public string $slug = '';

    public function mount(): void
    {
        $this->name = $this->field->name;
        $this->slug = $this->field->slug;
    }
    public function save(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'regex:/^[آ-یء\s\x{200C}]+$/u',
                'unique:fields,name,' . $this->field->id,
            ],
            'slug' => [
                'required',
                'string',
                'min:2',
                'max:80',
                'regex:/\A[a-zA-Z]+(?:-[a-zA-Z]+)*\z/',
                'unique:fields,slug,' . $this->field->id,
            ],
        ]);

        $this->field->update($validated);

        $this->modal('edit-field-' . $this->field->id)->close();

        $this->dispatch('field-updated', id: $this->field->id);
    }
    public function reset_prop(): void
    {
        $this->resetErrorBag();
    }

};
?>

<div>
    <flux:tooltip content="ویرایش رشته" position="bottom">
        <flux:button
            icon="pencil-square"
            size="xs"
            variant="subtle"
            color="amber"
            class="cursor-pointer transition-colors duration-200"
            x-on:click="$flux.modal('edit-field-{{ $field->id }}').show()"
        />
    </flux:tooltip>


    <flux:modal flyout
        name="edit-field-{{ $field->id }}"
        :show="$errors->isNotEmpty()"
        focusable
        class="md:w-96"
        @close="reset_prop"
        :dismissible="false"
    >
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{__('ویرایش رشته ')}}
                    <span class="font-bold text-yellow-500">{{ $field->name }}</span>
                </flux:heading>
                <flux:text class="mt-2">
                    {{ __('اطلاعات رشته آموزشی را ویرایش کنید.') }}
                </flux:text>
            </div>

            <form wire:submit.prevent="save" class="flex flex-col gap-3" autocomplete="off">
                <x-my.flt-lbl
                    name="name"
                    label="نام رشته"
                    type="text"
                    inputmode="text"
                    required
                    maxlength="60"
                    autofocus
                    class="font-semibold text-base!"
                />

                <x-my.flt-lbl
                    name="slug"
                    label="Slug"
                    type="text"
                    inputmode="text"
                    dir="ltr"
                    required
                    maxlength="80"
                    class="font-semibold text-base!"
                />

                <div class="flex">
                    <flux:spacer />
                    <flux:button
                        type="submit"
                        variant="primary"
                        color="teal"
                        size="sm"
                        class="cursor-pointer"
                    >
                        {{ __('ذخیره تغییرات') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
