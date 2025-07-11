<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => ' inline-flex items-center px-4 py-2 bg-black/75 border border-gray-300 rounded-3xl font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150'
    ]) }}>
    {{ $slot }}
</button>
