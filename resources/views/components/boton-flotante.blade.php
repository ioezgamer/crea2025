<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'fixed bottom-6 right-6 inline-flex items-center justify-center w-14 h-14 text-white bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:bg-blue-700 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 transform'
]) }}>
    <!-- Ícono de guardar -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
    </svg>
</button>