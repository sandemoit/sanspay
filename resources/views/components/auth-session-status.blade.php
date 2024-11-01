@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-success text-sm font-medium']) }}>
        {{ $status }}
    </div>
@endif
