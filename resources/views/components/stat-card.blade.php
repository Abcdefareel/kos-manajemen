@props(['label', 'value', 'icon', 'variant' => 'default', 'detail' => null])

@php
    $primary = $variant === 'primary';
@endphp

<section class="{{ $primary ? 'bg-primary text-white' : 'bg-surface text-ink' }} rounded-3xl p-5 shadow-soft">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="{{ $primary ? '!text-white/75' : 'text-muted' }} section-label">{{ $label }}</p>
            <p class="mt-3 text-3xl font-extrabold tracking-tight">{{ $value }}</p>
            @if ($detail)<p class="{{ $primary ? 'text-white/75' : 'text-muted' }} mt-1 text-xs">{{ $detail }}</p>@endif
        </div>
        <span class="{{ $primary ? 'bg-white/15 text-white' : 'bg-periwinkle text-indigo' }} flex size-12 items-center justify-center rounded-2xl">
            {!! $icon !!}
        </span>
    </div>
</section>
