@props(['active', 'count' => null])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-1 text-white bg-indigo-600 bg-opacity-75 rounded-full px-2 py-1 font-medium'
            : 'flex items-center space-x-1 text-gray-300 hover:bg-gray-700 hover:text-white rounded-full px-2 py-1 transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center space-x-1">
        {{ $slot }}
    </div>
    @if($count !== null)
        <span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
            {{ $count }}
        </span>
    @endif
</a>
