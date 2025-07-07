@props(['active' => false, 'count' => null])

@php
// Define the base classes shared by both active and inactive states
$baseClasses = 'group flex items-center px-1.5 py-2 rounded-full text-[10px] transition-colors duration-300 ease-in-out';

// Define classes for the inactive state
$inactiveClasses = 'text-slate-600 dark:text-slate-300 hover:bg-indigo-100 dark:hover:bg-slate-700 hover:text-indigo-700 dark:hover:text-indigo-400 hover:scale-[1.02] hover:transition-transform hover:duration-300 hover:ease-in';

// Define classes for the active state
$activeClasses = 'bg-violet-100/85 text-violet-800 dark:bg-purple-700/30 dark:text-white/85 font-semibold backdrop-blur-3xl';

// Combine base classes with state-specific classes
$classes = $baseClasses . ' ' . (($active ?? false) ? $activeClasses : $inactiveClasses);

// Define classes for the count badge
$countBaseClasses = 'text-[10px] px-1 py-0.5 rounded-3xl ml-0.5 transition-opacity duration-300 ease-in';
$countActiveClasses = 'bg-gradient-to-br from-violet-700 via-purple-700 to-purple-800 text-white dark:bg-indigo-500 dark:text-white';
$countInactiveClasses = 'bg-gradient-to-br from-violet-700 via-purple-700 to-purple-800 text-white dark:bg-indigo-600 dark:text-indigo-100 group-hover:bg-indigo-600 dark:group-hover:bg-indigo-500 ';
$countClasses = $countBaseClasses . ' ' . (($active ?? false) ? $countActiveClasses : $countInactiveClasses);

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{-- The slot will contain the icon and the text span --}}
    {{-- Example structure expected within the slot:
        <div class="flex items-center space-x-1.5">
            <svg class="w-5 h-5" ...></svg>
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
