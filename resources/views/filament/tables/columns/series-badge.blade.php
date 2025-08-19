@php
    $state = $getState();
    $record = $state['record'] ?? null;
    $value = $state['value'] ?? '';
@endphp

<div class="fi-size-sm grid gap-y-1 px-3 py-4">
    <span style="background-color: {{ $record->background_colour }};color: {{ $record->text_colour }}" class="px-2 py-1 rounded uppercase text-xs font-bold">
        {{ $value }}
    </span>
</div>
