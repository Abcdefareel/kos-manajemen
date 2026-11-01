@props(['paginator' => null, 'label' => 'Data'])

<section {{ $attributes->class(['overflow-hidden rounded-3xl bg-surface shadow-soft']) }}>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
    @if ($paginator)
        <footer class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-muted">
                Menampilkan {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}
                dari {{ $paginator->total() }} {{ $label }}
            </p>
            <nav aria-label="Pagination" class="flex items-center gap-2">
                @if ($paginator->previousPageUrl())
                    <a href="{{ $paginator->previousPageUrl() }}" class="rounded-full bg-neutral px-4 py-2 text-sm font-bold text-ink hover:bg-neutral/70">Sebelum</a>
                @else
                    <span class="cursor-not-allowed rounded-full bg-neutral/60 px-4 py-2 text-sm font-bold text-muted/60">Sebelum</span>
                @endif
                <span class="px-2 text-xs font-bold text-muted">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
                @if ($paginator->nextPageUrl())
                    <a href="{{ $paginator->nextPageUrl() }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-white shadow-primary">Lanjut</a>
                @else
                    <span class="cursor-not-allowed rounded-full bg-primary/40 px-4 py-2 text-sm font-bold text-white/80">Lanjut</span>
                @endif
            </nav>
        </footer>
    @endif
</section>
