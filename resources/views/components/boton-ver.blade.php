<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-2xl font-semibold text-sm text-white uppercase tracking-wide hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 active:from-green-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 transform space-x-2'
]) }}>
    <!-- Ícono Heroicon "eye" -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
    <span>{{ $slot }}</span>
</button>
