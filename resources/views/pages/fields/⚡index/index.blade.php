<div class="flex flex-col gap-1">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <flux:heading size="lg">رشته های آموزشی</flux:heading>

            <flux:text>
                مدیریت رشته های آموزشی
            </flux:text>
            <livewire:pages::fields.create/>
        </div>
    </div>

    <flux:separator variant="subtle"/>

    {{-- Table --}}
    <flux:table :paginate="$this->fields" class="inline transition-opacity duration-300" wire:loading.class="opacity-30" wire:target="sort">
        {{-- Columns --}}
        <flux:table.columns>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'id'"
                :direction="$sortDirection"
                wire:click="sort('id')"
            >                شناسه
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'name'"
                :direction="$sortDirection"
                wire:click="sort('name')"
            >                نام حوزه
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'slug'"
                :direction="$sortDirection"
                wire:click="sort('slug')"
            >Slug
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'is_active'"
                :direction="$sortDirection"
                wire:click="sort('is_active')"
            >
                وضعیت
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'created_at'"
                :direction="$sortDirection"
                wire:click="sort('created_at')"
            >
                تاریخ ایجاد
            </flux:table.column>

        </flux:table.columns>

        {{-- Rows --}}
        <flux:table.rows>
            @forelse ($this->fields as $field)
                <flux:table.row
                    :key="$field->id"
                    class="{{ $highlightedId === $field->id ? 'bg-zinc-50 dark:bg-zinc-800' : '' }}"
                >
                    <flux:table.cell>
                        {{ $field->id }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $field->name }}
                    </flux:table.cell>

                    <flux:table.cell class="font-instrument-sans">
                        {{ $field->slug }}
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($field->is_active)
                            <flux:badge color="green" size="sm">
                                فعال
                            </flux:badge>
                        @else
                            <flux:badge color="zinc" size="sm">
                                غیرفعال
                            </flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <x-my.jalali_cell :date="$field->created_at" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5">
                        <div class="py-8 text-center text-zinc-500">
                            هیچ حوزه‌ای ثبت نشده است.
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
