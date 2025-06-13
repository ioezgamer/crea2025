<x-guest-layout>
    {{-- La tarjeta ahora tiene un ancho máximo y se centra automáticamente --}}
    <div class="w-full max-w-lg p-6 shadow-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl">
        <div class="text-center">
            <h2 class="text-2xl font-bold tracking-tight text-transparent sm:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                Crear una nueva cuenta
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 text-balance">
                Completa el formulario para unirte.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nombre completo')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre y apellido" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres"/>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña"/>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex flex-col items-center justify-between gap-4 pt-4 mt-8 border-t sm:flex-row border-slate-200 dark:border-slate-700">
                <a class="text-sm font-medium text-indigo-600 transition duration-150 ease-in-out dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline" href="{{ route('login') }}">
                    {{ __('¿Ya estás registrado?') }}
                </a>

                <x-primary-button type="submit" class="w-full sm:w-auto">
                    {{ __('Registrar') }}
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </x-primary-button>
            </div>
        </form>
    </div>

    {{-- El footer ahora está en el layout principal si se desea, o se puede omitir para una vista más limpia --}}
</x-guest-layout>
