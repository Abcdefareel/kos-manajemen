@props(['title', 'description' => null])

<div {{ $attributes->class(['rounded-3xl border-2 border-dashed border-neutral bg-surface-alt/70 px-6 py-12 text-center']) }}>
    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-periwinkle text-indigo">
        <svg viewBox="0 0 24 24" class="size-7" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 10.5 12 4l8 6.5V20H4v-9.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 20v-6h6v6"/></svg>
    </div>
    <h3 class="mt-4 font-bold text-ink">{{ $title }}</h3>
    @if ($description)<p class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted">{{ $description }}</p>@endif
    @if (isset($slot) && trim($slot) !== '')<div class="mt-5">{{ $slot }}</div>@endif
</div>
