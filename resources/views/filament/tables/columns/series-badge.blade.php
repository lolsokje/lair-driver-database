@if ($getState() !== null)
    <div class="fi-size-sm grid gap-y-1 px-3 py-4">
        <span style="background-color: {{ $getState()->background_colour }};color: {{ $getState()->text_colour }}" class="px-2 py-1 rounded uppercase text-xs font-bold">
            {{ $getState()->name }}
        </span>
    </div>
@endif
