@props(['active'])

@php
$classes = ($active ?? false)
    ? 'flex items-center justify-between space-x-3 text-white bg-indigo-800 bg-opacity-20 rounded-lg px-3 py-2 font-medium'
    : 'flex items-center justify-between space-x-3 text-gray-300 hover:bg-gray-800 rounded-lg px-3 py-2 transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center space-x-3" x-show="sidebarOpen && window.innerWidth >= 640">
        {{ $slot }}
    </div>
    <div class="flex items-center" x-show="!sidebarOpen && window.innerWidth >= 640">
        <span class="sr-only">{{ $slot }}</span> <!-- Oculta el texto para lectores de pantalla -->
    </div>
    <span x-show="sidebarOpen && window.innerWidth >= 640" class="text-xs bg-blue-600 rounded-full px-2 py-1">{{ $attributes->get('count') }}</span>
</a>