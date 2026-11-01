<section {{ $attributes->class(['rounded-3xl bg-pink/15 p-4 sm:p-5']) }}>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
        <span class="hidden size-11 shrink-0 items-center justify-center rounded-full bg-pink/30 text-primary-dark lg:flex">
            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg>
        </span>
        <div class="min-w-0 flex-1">{{ $slot }}</div>
    </div>
</section>
