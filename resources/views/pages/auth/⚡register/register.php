<?php

use App\Support\Digits;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::auth')] #[Title('ثبت نام')] class extends Component
{
    public string $mobile = '';

    protected function rules(): array
    {
        return [
            'mobile' => ['bail', 'required', 'digits:11', 'starts_with:09'],
        ];
    }

    public function submit(): void
    {
        $this->mobile = Digits::toEnglish($this->mobile);

        $this->validate();
    }
};
