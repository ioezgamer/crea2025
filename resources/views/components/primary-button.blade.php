<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 text-xs font-semibold text-white tracking-widest transition-all duration-150 ease-in-out border border-transparent rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 group hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 uppercase ']) }}>
    {{ $slot }}
</button>
