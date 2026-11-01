@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
    'ariaLabel' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-bold transition focus:ring-4 focus:ring-pink/50 disabled:cursor-not-allowed disabled:opacity-50';
    $variants = [
        'primary' => 'bg-primary text-white shadow-primary hover:bg-primary-dark',
        'secondary' => 'bg-neutral text-ink hover:bg-neutral/70',
        'outline' => 'bg-transparent text-primary ring-1 ring-primary/30 hover:bg-pink/20',
        'icon' => 'size-10 rounded-full bg-surface-alt p-0 text-muted hover:bg-periwinkle hover:text-indigo',
        'danger' => 'bg-primary text-white shadow-primary hover:bg-primary-dark',
        'text' => 'bg-transparent px-3 text-muted hover:text-primary',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$base, $variants[$variant] ?? $variants['primary']]) }} @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$base, $variants[$variant] ?? $variants['primary']]) }} @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif>
        {{ $slot }}
    </button>
@endif
