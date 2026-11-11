
@props([
    'name',
    'id' => '',
    'label',
    'model' => null,
    'modelModifier' => '',
    'type' => 'text',
    'dir' => 'rtl',
    'required' => true,
    'autocomplete' => 'off',
    'disabled' => false,
    'readonly' => false,
])

@php
    $inputId = $id ?: $name;
    $hasError = $errors->has($name);
    $errorId = $inputId . '-error';
    $borderClass = $hasError
    ? 'border-red-500 focus:border-red-500'
    : 'border-gray-400';
@endphp

<div class="relative">

    <input
        id="{{ $inputId }}"
        type="{{ $type }}"
        wire:model{{ $modelModifier !== '' ? '.' . $modelModifier : '' }}="{{ $model ?? $name }}"
        placeholder=" "
        autocomplete="{{ $autocomplete }}"
        dir="{{ $dir }}"
        @required($required)
        @disabled($disabled)
        @readonly($readonly)
        @if ($hasError)
            aria-invalid="true"
        aria-describedby="{{ $errorId }}"
        @endif
        {{ $attributes->merge([
    'class' => "
        peer
        w-full rounded-lg border-2 {$borderClass}
        p-2.5 text-center font-medium text-stone-600
        transition-colors duration-200
        focus:outline-none focus:text-blue-600
    ",
]) }}
    >

    <label
        for="{{ $inputId }}"
        class="
            absolute right-3 top-3 text-sm
            {{ $hasError ? 'text-red-600 dark:text-red-400' : 'text-stone-600 dark:text-stone-400' }}
            transition-all duration-200

            peer-focus:-top-2
            peer-focus:right-3.5
            peer-focus:bg-white
            peer-focus:px-1
            peer-focus:text-xs
            peer-focus:font-medium
            peer-focus:text-blue-600

            peer-not-placeholder-shown:-top-2
            peer-not-placeholder-shown:right-3
            peer-not-placeholder-shown:bg-white
            peer-not-placeholder-shown:px-1
            peer-not-placeholder-shown:text-xs
            peer-not-placeholder-shown:font-medium
        "
    >
        {{ $label }}
    </label>

    @if ($hasError)
        <p
            id="{{ $errorId }}"
            class="mt-1 text-center text-sm font-light text-red-600 dark:text-red-400"
        >
            {{ $errors->first($name) }}
        </p>
    @endif

</div>
