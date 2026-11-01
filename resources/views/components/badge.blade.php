@props(['status', 'label' => null])

@php
    $style = match ($status) {
        'terisi' => 'bg-magenta-soft/30 text-primary-dark [&>span]:bg-magenta-soft',
        'dipesan', 'aktif' => 'bg-success-soft text-ink [&>span]:bg-success',
        'kosong', 'tersedia' => 'bg-pink/25 text-primary-dark [&>span]:bg-pink',
        'info' => 'bg-periwinkle text-indigo [&>span]:bg-indigo',
        'terlambat', 'dibatalkan', 'gagal' => 'bg-danger/15 text-primary-dark [&>span]:bg-danger',
        'tertunda' => 'bg-neutral text-muted [&>span]:bg-muted',
        'perbaikan' => 'bg-surface-alt text-muted [&>span]:bg-muted',
        default => 'bg-neutral text-ink [&>span]:bg-muted',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold', $style]) }}>
    <span class="size-1.5 rounded-full"></span>
    {{ $label ?? ucfirst($status) }}
</span>
