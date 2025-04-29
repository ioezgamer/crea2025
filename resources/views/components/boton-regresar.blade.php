<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center w-12 h-12 border border-transparent bg-gray-100 text-slate-300 rounded-full font-semibold text-sm tracking-wide focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 hover:text-slate-900 hover:bg-gray-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md transform'
]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
</button>