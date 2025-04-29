<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-700 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50'
]) }}>
    <!-- Ícono de guardar -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
    </svg>
</button>