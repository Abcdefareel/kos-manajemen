@props(['name', 'label', 'checked' => false, 'value' => '1'])

<label class="inline-flex cursor-pointer items-center gap-3 rounded-full bg-surface-alt px-4 py-3 text-sm font-semibold text-ink transition has-[:checked]:bg-pink/25 has-[:checked]:text-primary-dark">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked(old($name, $checked)) class="size-4 accent-primary focus:ring-4 focus:ring-pink/50">
    <span>{{ $label }}</span>
</label>
