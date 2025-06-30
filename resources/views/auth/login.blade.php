<x-guest-layout>
    {{-- Tarjeta principal de inicio de sesión --}}
    <div class="w-full max-w-md p-8 mx-auto space-y-8 transition-shadow duration-500 ease-in-out transform border shadow-md bg-gradient-to-b from-blue-500/10 to-transparent border-violet-200 rounded-3xl hover:shadow-xl">

        {{-- Logo y Título --}}

        {{-- Encabezado con Logo y Título --}}
        <div class="text-center">
            <a href="/" class="inline-block mb-4">
                <x-application-logo class="w-24 h-24 mx-auto fill-current text-violet-500" />
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Bienvenido a SistemaCREA
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">
                Inicia sesión para acceder a tu panel.
            </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- Formulario de Login --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Campo de Correo Electrónico --}}
            <div >
                <x-input-label for="email" value="Correo Electrónico" class="sr-only " />
                <x-text-input id="email" class="block w-full mt-1 " type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Correo electrónico" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Campo de Contraseña --}}
            <div>
                <x-input-label for="password" value="Contraseña" class="sr-only" />
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" placeholder="Contraseña" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Opciones (Recordarme y Olvidé mi contraseña) --}}
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:border-slate-600 dark:bg-slate-700 focus:ring-indigo-500 dark:focus:ring-offset-slate-800" name="remember">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 transition duration-150 ease-in-out dark:text-indigo-400 hover:text-indigo-500 hover:underline" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            {{-- Botón de Envío --}}
            <div class="flex items-center justify-center">
                <x-primary-button type="submit" >
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>
            </div>

            {{-- Enlace a Registro --}}
            @if (Route::has('register'))
            <div class="pt-4 text-sm text-center border-t dark:border-slate-700">
                <span class="text-gray-600 dark:text-slate-400">¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-500 hover:underline">
                    Regístrate aquí
                </a>
            </div>
            @endif
        </form>
    </div>
</x-guest-layout>
