@props(['active' => false, 'count' => null])

@php
// Define the base classes shared by both active and inactive states
$baseClasses = 'group flex items-center px-3 py-2 rounded-2xl text-sm transition-colors duration-150 ease-in-out';

// Define classes for the inactive state
$inactiveClasses = 'text-slate-600 hover:bg-indigo-100 hover:text-indigo-700 transition-all duration-700 ease-in-out hover:scale-110';

// Define classes for the active state
$activeClasses = 'bg-indigo-100 text-indigo-700 font-semibold ';

// Combine base classes with state-specific classes
$classes = $baseClasses . ' ' . (($active ?? false) ? $activeClasses : $inactiveClasses);

// Define classes for the count badge
$countBaseClasses = 'text-xs  px-1.5 py-0.5 rounded-full ml-auto transition-opacity duration-300 ease-in';
$countActiveClasses = 'bg-indigo-600 text-white';
$countInactiveClasses = 'bg-indigo-500 text-white group-hover:bg-indigo-600';
$countClasses = $countBaseClasses . ' ' . (($active ?? false) ? $countActiveClasses : $countInactiveClasses);

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{-- The slot will contain the icon and the text span --}}
    {{-- Example structure expected within the slot:
        <div class="flex items-center space-x-1.5">
            <svg class="h-5 w-5" ...></svg>
            <span>Link Text</span>
        </div>
    --}}
    {{ $slot }}

    @if($count !== null && $count > 0)
        <span class="{{ $countClasses }}">
            {{ $count }}
        </span>
    @endif
</a>
