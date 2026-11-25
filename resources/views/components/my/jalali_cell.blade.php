@php use App\Support\Date\DateFormatter; @endphp
@props(['date' => null])

@php
    $formattedDate = DateFormatter::format($date);
    $weekday = jdate('l', $date?->getTimestamp() ?? '', '', 'local', 'en');

    [$datePart, $timePart] = array_pad(
        explode(' ', $formattedDate, 2),
        2,
        ''
    );

    [$hour, $minute, $second] = array_pad(
        explode(':', $timePart),
        3,
        ''
    );
@endphp

<div class="leading-tight">
    <div class="text-right" dir="ltr">
        {{ str_replace('/', ' / ', $datePart) }}
    </div>
    <div class="text-xs">
        <span dir="ltr">{{ $hour }} : {{ $minute }}</span>
        <span class="text-stone-500 text-xs">{{ $weekday }}</span>


    </div>
</div>
