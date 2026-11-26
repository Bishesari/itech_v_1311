<div class="flex flex-col gap-1">
    {{-- Header --}}
    <div class="flex space-x-2">
        <flux:icon.academic-cap class="size-6"/>
        <flux:heading size="lg" level="1">{{ __('رشته‌های آموزشی') }}</flux:heading>
    </div>
    <div class="flex justify-between">
        <flux:text class="text-sm">{{ __('مدیریت و سازمان‌دهی رشته‌های آموزشی مؤسسه') }}</flux:text>
        <livewire:pages::fields.create/>
    </div>
    <flux:separator variant="subtle"/>


    {{-- Table --}}
    <flux:table :paginate="$this->fields" class="inline transition-opacity duration-300" wire:loading.class="opacity-30" wire:target="sort">
        {{-- Columns --}}
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'id'" :direction="$sortDirection" wire:click="sort('id')">
                {{__('شناسه')}}
            </flux:table.column>

            <flux:table.column align="center" sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">
                {{__('نام رشته')}}
            </flux:table.column>

            <flux:table.column align="center" dir="ltr" sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection" wire:click="sort('slug')">
                {{__('Slug')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection" wire:click="sort('is_active')">
                {{__('وضعیت')}}
            </flux:table.column>

            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">
                {{__('تاریخ ایجاد')}}
            </flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection" wire:click="sort('updated_at')">
                {{__('تاریخ ویرایش')}}
            </flux:table.column>

            <flux:table.column align="center">
                {{ __('عملیات') }}
            </flux:table.column>

        </flux:table.columns>

        {{-- Rows --}}
        <flux:table.rows>
            @forelse ($this->fields as $field)
                @php
                    $class = $highlightedId === $field->id
                        ? 'bg-green-100/80 dark:bg-green-900/30'
                        : '';
                @endphp

                <flux:table.row :key="$field->id" x-data="{
                highlighted: @js($highlightedId === $field->id),
                init() {
                    if (this.highlighted) {
                        setTimeout(() => this.highlighted = false, 2000)}}}"
                                x-bind:class="highlighted ? 'bg-green-100/80 dark:bg-green-900/30' : ''"
                                class="transition-colors duration-300 hover:bg-zinc-100 dark:hover:bg-stone-900/80"
                >
                    <flux:table.cell class="font-mono pr-2!">
                        {{ $field->id }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $field->name }}
                    </flux:table.cell>

                    <flux:table.cell class="font-mono text-left">
                        {{ $field->slug }}
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($field->is_active)
                            <flux:badge color="green" size="sm">{{__('فعال')}}</flux:badge>
                        @else
                            <flux:badge color="zinc" size="sm">{{__('غیرفعال')}}</flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <x-my.jalali_cell :date="$field->created_at" />
                    </flux:table.cell>

                    <flux:table.cell>
                        <x-my.jalali_cell :date="$field->updated_at" />
                    </flux:table.cell>

                    <flux:table.cell align="center">
                        <div class="inline-flex items-center justify-center gap-2">
                            <livewire:pages::fields.update
                                :$field
                                :key="'field-edit-'.$field->id"
                            />
                        </div>
                    </flux:table.cell>



                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5">
                        <div class="py-8 text-center text-zinc-500">
                            {{__('هیچ حوزه‌ای ثبت نشده است.')}}
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
