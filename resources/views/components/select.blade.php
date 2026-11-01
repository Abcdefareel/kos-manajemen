@props(['name', 'label', 'required' => false])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-muted">
        {{ $label }} @if($required)<span class="text-primary" aria-hidden="true">*</span>@endif
    </label>
    <select id="{{ $name }}" name="{{ $name }}" @required($required)
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->except('class')->class(['field-control appearance-none']) }}>
        {{ $slot }}
    </select>
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-xs font-semibold text-primary" role="alert">{{ $message }}</p>@enderror
</div>
