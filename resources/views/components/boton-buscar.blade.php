<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-end justify-center w-12 h-12 text-black transition-all duration-200 transform hover:scale-110'
]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M21 21l-4.35-4.35M18 10a8 8 0 11-16 0 8 8 0 0116 0z" />
    </svg>
</button>