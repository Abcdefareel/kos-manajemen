@props(['name', 'label', 'type' => 'text', 'value' => null, 'placeholder' => null, 'required' => false, 'hint' => null])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-muted">
        {{ $label }} @if($required)<span class="text-primary" aria-hidden="true">*</span>@endif
    </label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->except('class')->class(['field-control']) }}
    >
    @if($hint)<p class="mt-2 text-xs text-muted">{{ $hint }}</p>@endif
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-xs font-semibold text-primary" role="alert">{{ $message }}</p>@enderror
</div>
