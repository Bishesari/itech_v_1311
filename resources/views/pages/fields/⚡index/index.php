<?php

use App\Models\Field;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new
#[Title('رشته های آموزشی')]
class extends Component
{
    use WithPagination;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public ?int $highlightedId = null;

    public int $perPage = 10;

    private const SORTABLE_COLUMNS = [
        'id',
        'name',
        'slug',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function sort(string $column): void
    {
        if (! in_array($column, self::SORTABLE_COLUMNS, true)) {
            return;
        }

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[Computed]
    public function fields(): LengthAwarePaginator
    {
        $sortBy = in_array($this->sortBy, self::SORTABLE_COLUMNS, true)
            ? $this->sortBy
            : 'name';

        $sortDirection = in_array($this->sortDirection, ['asc', 'desc'], true)
            ? $this->sortDirection
            : 'asc';

        return Field::query()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($this->perPage);
    }

    #[On('field-created')]
    public function afterCreate(int $id): void
    {
        $this->focusRecord($id);
        Flux::toast(
            heading: 'ثبت شد.',
            text: 'رشته جدید با موفقیت ثبت شد.',
            variant: 'success',
        );
    }

    #[On('field-updated')]
    public function afterUpdate(int $id): void
    {
        $this->focusRecord($id);

        Flux::toast(
            heading: 'تغییرات اعمال شد.',
            text: 'اطلاعات حوزه با موفقیت ویرایش شد.',
            variant: 'warning',
        );
    }

    private function focusRecord(int $id): void
    {
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();

        $field = Field::query()->find($id);

        if (! $field) {
            return;
        }

        $position = Field::query()
            ->where('name', '<', $field->name)
            ->count();

        $page = intdiv($position, $this->perPage) + 1;

        $this->gotoPage($page);

        $this->highlightedId = $field->id;
    }
    public function updatingPage(): void
    {
        $this->highlightedId = null;
    }
};
