@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'block w-full text-xl border-gray-300 rounded-xl shadow-sm
                dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300
                focus:border-indigo-500 dark:focus:border-indigo-600
                focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600'
]) !!}>
