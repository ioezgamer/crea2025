<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full shadow-md transition-all duration-200 hover:bg-blue-700 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50'
]) }}>
    <!-- Ícono Heroicon "pencil" -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>
</button>
