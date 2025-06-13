<x-guest-layout>
    {{-- La tarjeta ahora tiene un ancho máximo y se centra automáticamente por el layout padre --}}
    <div class="w-full max-w-md p-6 shadow-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl">
        <div class="text-center">
            <h2 class="text-2xl font-bold tracking-tight text-transparent sm:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                ¡Bienvenido de nuevo!
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 text-balance">
                Inicia sesión para continuar en el SistemaCREA.
            </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mt-4 mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@correo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-indigo-600 transition duration-150 ease-in-out sm:text-sm dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña"/>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:border-slate-500 dark:bg-slate-700 focus:ring-indigo-500 dark:focus:ring-offset-slate-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 pt-4 mt-8 border-t sm:flex-row border-slate-200 dark:border-slate-700">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-medium text-indigo-600 transition duration-150 ease-in-out dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline">
                        ¿No tienes una cuenta?
                    </a>
                @endif

                <x-primary-button type="submit" class="w-full sm:w-auto">
                    {{ __('Iniciar Sesión') }}
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1"></path></svg>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
