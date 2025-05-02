@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center justify-between space-x-3 text-gray-900 bg-indigo-50 rounded-md px-3 py-2 font-medium'
            : 'flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center space-x-3">
        {{ $slot }}
    </div>
    <span class="text-xs text-gray-500">{{ $attributes->get('count') }}</span>
</a>