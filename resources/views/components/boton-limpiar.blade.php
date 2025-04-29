<button {{ $attributes->merge([
    'type' => 'reset',
    'class' => 'inline-flex items-end justify-center w-12 h-12 text-red-600 transition-all duration-200 transform hover:scale-110'
]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 20 20"
         stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>
