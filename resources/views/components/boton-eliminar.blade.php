<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-5 py-3 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-2xl font-semibold text-sm text-white uppercase tracking-wide hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-200 focus:ring-offset-2 active:from-red-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 transform space-x-2'
]) }}>
    <!-- Ícono Heroicon "trash" -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h.01M4 7h16" />
    </svg>
    <span>{{ $slot }}</span>
</button>
