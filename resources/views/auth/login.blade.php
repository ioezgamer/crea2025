{{-- Assuming this is a Blade component, x-guest-layout provides the basic page structure --}}
<x-guest-layout>
    {{-- Centering container for the login card, applying the gradient background --}}
    <div class="flex flex-col items-center justify-center min-h-lg sm:p-6 lg:p-8 antialiased">

        {{-- Login Card with two-column layout for larger screens --}}
        <div class="flex bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden mx-auto max-w-sm lg:max-w-4xl w-full my-6 sm:my-8 transform transition-all duration-500 ease-in-out hover:shadow-3xl">
            
            {{-- Image Section (visible on large screens) --}}
            <div class="hidden lg:flex lg:items-center lg:justify-center lg:w-1/2 bg-cover bg-center"
                 style="background-image:url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80');">
                {{-- Overlay content can be added here if desired --}}
                <div class="p-12 text-center rounded-xl">
                    <h2 class="text-3xl font-bold text-white mb-3 text-balance">Bienvenido a CREA</h2>
                    <p class="text-slate-200 text-balance">Donde tus ideas toman forma y tus proyectos alcanzan el éxito.</p>
                </div>
            </div>

            {{-- Form Section --}}
            <div class="w-full p-6 sm:p-8 lg:w-1/2">
                <div class="text-center mb-6 sm:mb-8">
                    {{-- Application Logo --}}
                    <a href="/" class="inline-block mb-4">
                         {{-- Using the same idea icon as the registration page for consistency --}}
                        <div class="mx-auto h-16 w-16 sm:h-20 sm:w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-1 shadow-lg">
                            <div class="flex h-full w-full items-center justify-center rounded-full bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <h2 class="text-2xl sm:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 tracking-tight text-balance">
                        Sistema CREA
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 text-balance">
                        ¡Bienvenido de nuevo! Por favor, inicia sesión.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      transition duration-150 ease-in-out text-sm sm:text-base"
                               placeholder="tu@correo.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-slate-700">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline font-medium transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      transition duration-150 ease-in-out text-sm sm:text-base"
                               placeholder="Tu contraseña">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
                    </div>

                    {{-- Remember Me Checkbox --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0 transition duration-150 ease-in-out" name="remember">
                            <span class="ml-2 text-sm text-slate-600">Recordarme</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit"
                                class="group relative w-full flex justify-center items-center text-base font-semibold px-6 py-3 border-2 border-transparent rounded-full transition-all duration-300 ease-in-out
                                       bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <span>{{ __('Iniciar sesión') }}</span>
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>
                </form>

                @if (Route::has('register'))
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <p class="text-sm text-center text-slate-600">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition duration-150 ease-in-out">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        <footer class="mt-8 sm:mt-12 text-center">
            <p class="text-xs sm:text-sm text-slate-600">
                &copy; {{ date('Y') }} Sistema CREA. Todos los derechos reservados.
            </p>
        </footer>
    </div>
</x-guest-layout>


