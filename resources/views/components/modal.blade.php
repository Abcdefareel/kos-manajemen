@props(['title', 'subtitle' => null, 'cancelUrl'])

<div data-modal class="fixed inset-0 z-40 flex items-center justify-center overflow-y-auto bg-primary-dark/25 p-4 backdrop-blur-md">
    <section role="dialog" aria-modal="true" aria-labelledby="modal-title" class="relative my-auto max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-modal bg-surface p-6 shadow-soft sm:p-9">
        <a href="{{ $cancelUrl }}" data-modal-close aria-label="Tutup formulir" class="absolute right-5 top-5 flex size-10 items-center justify-center rounded-full bg-surface-alt text-muted transition hover:bg-pink/30 hover:text-primary-dark">
            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/></svg>
        </a>
        <h1 id="modal-title" class="pr-12 text-2xl font-extrabold tracking-tight text-primary-dark sm:text-3xl">{{ $title }}</h1>
        @if($subtitle)<p class="mt-2 text-sm leading-6 text-muted">{{ $subtitle }}</p>@endif
        <div class="mt-7">{{ $slot }}</div>
    </section>
</div>
