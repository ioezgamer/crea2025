@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-1 text-white bg-indigo-600 bg-opacity-75 rounded-full px-2 py-1 font-medium'
            : 'flex items-center space-x-1 text-gray-300 hover:bg-gray-700 hover:text-white rounded-full px-2 py-1 transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center space-x-1">
        {{ $slot }}
    </div>
    <span class="text-xs bg-blue-600 rounded-full px-1 py-0.5">{{ $attributes->get('count') }}</span>
</a>