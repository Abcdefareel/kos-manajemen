@props(['name', 'label', 'accept' => 'image/*', 'hint' => null])

<div>
    <label for="{{ $name }}" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-muted">{{ $label }}</label>
    <label for="{{ $name }}" class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-pink bg-pink/10 px-6 py-8 text-center transition hover:bg-pink/20">
        <span class="flex size-12 items-center justify-center rounded-full bg-pink/30 text-primary-dark">
            <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h3l1.5-2h7L17 8h3v11H4V8Z"/><circle cx="12" cy="13" r="3.5"/></svg>
        </span>
        <span class="mt-3 text-sm font-bold text-ink">Pilih gambar untuk diunggah</span>
        @if($hint)<span class="mt-1 text-xs text-muted">{{ $hint }}</span>@endif
        <input id="{{ $name }}" name="{{ $name }}" type="file" accept="{{ $accept }}" class="sr-only">
    </label>
    @error($name)<p class="mt-2 text-xs font-semibold text-primary" role="alert">{{ $message }}</p>@enderror
</div>
