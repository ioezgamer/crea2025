<a href="{{ $href }}"
   class="flex items-center space-x-2 group w-fit"
   aria-label="Volver a {{ $text }}">
    {{-- Icono de flecha hacia la izquierda --}}
    <svg class="w-5 h-5 text-indigo-600 transition group-hover:text-indigo-800"
         fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>

    {{-- Texto con gradiente --}}
    <h2 class="text-xl font-bold text-transparent lg:text-2xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
        {{ $text }}
    </h2>
</a>
