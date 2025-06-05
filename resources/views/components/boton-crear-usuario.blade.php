<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'group inline-flex items-center justify-center text-blue-700 w-12 h-12 bg-white rounded-full shadow-md transition-all duration-300 transform hover:rotate-90 focus:outline-none stroke-zinc-900 fill-none hover:fill-zinc-800 active:stroke-zinc-200 active:fill-zinc-600'
]) }}>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-12 h-12 transition-all duration-300 group-active:stroke-zinc-200 group-active:fill-zinc-600 group-active:duration-0"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
    >
        <path
            d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"
        />
        <path d="M8 12H16" />
        <path d="M12 16V8" />
    </svg>
   {{ $slot }}
</button>
