<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new
#[Layout('layouts::auth')]
#[Title('ثبت نام')]
class extends Component
{
    #[Validate('bail|required|digits:11|starts_with:09')]
    public string $mobile = '';

    public function save(){
        $this->validate();

    }
};
