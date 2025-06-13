@props([
    'type' => 'info', // Tipo por defecto
    'title' => '',
    'message' => ''
])

@php
    $baseClasses = 'p-4 mb-6 rounded-xl shadow-lg text-sm border-l-4 flex items-start space-x-3 bg-white/80 backdrop-blur-md';
    $iconClasses = 'h-5 w-5 flex-shrink-0 mt-0.5';
    $titleDivClasses = 'font-semibold block text-slate-800 dark:text-slate-100';
    $messageDivClasses = 'text-xs text-slate-600 dark:text-slate-300';

    $typeSpecificClasses = match ($type) {
        'success' => 'border-green-500',
        'error'   => 'border-red-500',
        'warning' => 'border-amber-500',
        'info'    => 'border-sky-500',
        default   => 'border-slate-500',
    };

    $iconColor = match ($type) {
        'success' => 'text-green-500 dark:text-green-400',
        'error'   => 'text-red-500 dark:text-red-400',
        'warning' => 'text-amber-500 dark:text-amber-400',
        'info'    => 'text-sky-500 dark:text-sky-400',
        default   => 'text-slate-500 dark:text-slate-400',
    };

    $iconSvg = match ($type) {
        'success' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        'error'   => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        'warning' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
        'info'    => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        default   => '',
    };
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeSpecificClasses, 'role' => 'alert']) }}>
    @if($iconSvg)
        {!! $iconSvg !!}
    @endif
    <div class="flex-grow">
        @if($title)
            <strong class="{{ $titleDivClasses }}">{{ $title }}</strong>
        @endif
        @if($message)
            <span class="{{ $messageDivClasses }}">{{ $message }}</span>
        @endif
        {{ $slot }}
    </div>
</div>
