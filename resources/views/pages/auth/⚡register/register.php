<?php

use App\Models\Mobile;
use App\Support\Digits;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::auth')] #[Title('ثبت نام')] class extends Component
{
    public string $number = '';

    protected function rules(): array
    {
        return [
            'number' => ['bail', 'required', 'digits:11', 'starts_with:09'],
        ];
    }

    public function submit(): void
    {
        $this->number = Digits::toEnglish($this->number);

        $this->validate();

        if (Mobile::query()
            ->where('number', $this->number)
            ->whereHas('people')
            ->exists()) {
            $this->addError('number', 'این شماره موبایل قبلاً در سیستم ثبت شده است.');

            return;
        }
    }
};
