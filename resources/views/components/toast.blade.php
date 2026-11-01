@props(['type' => 'success', 'message'])

<div data-toast role="{{ $type === 'success' ? 'status' : 'alert' }}" class="flex items-center gap-3 rounded-2xl px-5 py-4 shadow-soft transition duration-300 {{ $type === 'success' ? 'bg-success-soft text-ink' : 'bg-danger/15 text-primary-dark' }}">
    <span class="flex size-9 shrink-0 items-center justify-center rounded-full {{ $type === 'success' ? 'bg-success/15 text-success' : 'bg-danger/20 text-primary-dark' }}">
        @if($type === 'success')
            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
        @else
            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M10.3 3.9 1.8 18.5A1.7 1.7 0 0 0 3.3 21h17.4a1.7 1.7 0 0 0 1.5-2.5L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
        @endif
    </span>
    <p class="flex-1 text-sm font-semibold">{{ $message }}</p>
    <button type="button" data-toast-close aria-label="Tutup notifikasi" class="rounded-full p-1 text-current/70 hover:bg-white/50">
        <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"/></svg>
    </button>
</div>
