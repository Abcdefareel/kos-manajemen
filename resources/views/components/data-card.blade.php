@props(['title', 'subtitle' => null, 'badge' => null, 'icon' => null])

<article {{ $attributes->class(['rounded-3xl bg-surface p-5 shadow-soft']) }}>
    <div class="flex items-start gap-4">
        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-periwinkle text-indigo">
            @if ($icon){!! $icon !!}@else
                <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 0 0-16 0M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/></svg>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div><h3 class="font-bold text-ink">{{ $title }}</h3>@if ($subtitle)<p class="mt-1 text-sm text-muted">{{ $subtitle }}</p>@endif</div>
                @if ($badge)<x-badge :status="$badge" />@endif
            </div>
            @if (isset($details))<div class="mt-4">{{ $details }}</div>@endif
            @if (isset($actions))<div class="mt-4 flex flex-wrap items-center gap-2">{{ $actions }}</div>@endif
        </div>
    </div>
</article>
