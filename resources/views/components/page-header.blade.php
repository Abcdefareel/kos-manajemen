@props(['title', 'subtitle' => null])

<div class="mb-7 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="page-title">{{ $title }}</h1>
        @if ($subtitle)<p class="mt-2 max-w-2xl text-sm leading-6 text-muted">{{ $subtitle }}</p>@endif
    </div>
    @if (isset($actions))
        <div class="flex shrink-0 flex-wrap items-center gap-3">{{ $actions }}</div>
    @endif
</div>
