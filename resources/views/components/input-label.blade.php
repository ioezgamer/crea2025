@props(['value'])

<label {{ $attributes->merge(
    ['class' => 'block text-sm font-medium text-slate-800 dark:text-slate-950 rounded-3xl']) }}>
    {{ $value ?? $slot }}
</label>
