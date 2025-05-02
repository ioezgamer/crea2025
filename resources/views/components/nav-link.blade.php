@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-3 text-gray-900 bg-indigo-50 rounded-md px-3 py-2 font-medium'
            : 'flex items-center space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
   
</a>