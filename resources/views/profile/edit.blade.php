<x-app-layout>
    <x-slot name="header">
                <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <h2 class="text-xl font-semibold leading-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 dark:text-white">
            {{ __('Perfil de usuario') }}
        </h2>
                </div>
    </x-slot>

    <div class="min-h-screen py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">

            {{-- Alert for session status --}}
            @if (session('status'))
                <div class="relative px-4 py-3 text-green-700 border-l-4 border-green-500 shadow-lg bg-green-50/80 dark:bg-green-700/30 backdrop-blur-md dark:border-green-600 dark:text-green-200 rounded-xl animate-fade-in-down" role="alert">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 flex-shrink-0 mt-0.5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong class="font-bold text-green-800 dark:text-green-100">¡Éxito!</strong>
                            <span class="block text-sm text-green-700 sm:inline dark:text-green-200">{{ session('status') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Avatar + Role Section --}}
            <div class="p-6 shadow-xl sm:p-8 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="max-w-xl mx-auto space-y-6 text-center">
                    {{-- Avatar --}}
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8b5cf6&color=fff&rounded=true&size=120&font-size=0.40&bold=true"
                         alt="Avatar de {{ Auth::user()->name }}"
                         class="mx-auto border-4 border-white rounded-full shadow-lg h-28 w-28 sm:h-32 sm:w-32 dark:border-slate-700 ring-2 ring-purple-500/50 dark:ring-purple-400/50">

                    <div class="mt-4">
                        <h1 class="text-3xl font-bold sm:text-4xl text-slate-800 dark:text-white">{{ Auth::user()->name }}</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- User Role --}}
                    <div class="p-4 border shadow-inner sm:p-6 bg-slate-100/70 dark:bg-slate-700/50 rounded-xl border-slate-200/80 dark:border-slate-600/80">
                        <x-input-label for="role_display" :value="__('Rol Asignado')" class="mb-1 text-sm font-medium text-left text-slate-700 dark:text-slate-300" />
                        <p id="role_display" class="block w-full px-3 py-3 mt-1 text-lg font-semibold text-center text-indigo-700 bg-white border rounded-lg shadow-sm dark:text-indigo-400 dark:bg-slate-900 border-slate-300 dark:border-slate-600">
                            {{ ucfirst($user->role) }}
                        </p>
                        <p class="mt-3 text-xs text-left text-slate-500 dark:text-white">
                            {{ __('Nota: El rol de usuario solo puede ser modificado por un administrador del sistema.') }}
                        </p>
                    </div>

                    {{--
                    <div class="flex justify-center mt-6 space-x-6">
                        <a href="#" class="text-indigo-600 transition transform hover:text-indigo-500 hover:scale-110" title="Facebook">
                            <i class="fab fa-facebook-f fa-lg"></i>
                        </a>
                        <a href="#" class="transition transform text-sky-500 hover:text-sky-400 hover:scale-110" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-pink-600 transition transform hover:text-pink-500 hover:scale-110" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-blue-700 transition transform hover:text-blue-600 hover:scale-110" title="LinkedIn">
                            <i class="fab fa-linkedin-in fa-lg"></i>
                        </a>
                    </div>
                     <div class="mt-4 text-xs text-slate-400">

                    </div>
                    (Redes sociales podrían ir aquí si se implementan) --}}
                </div>
            </div>

            {{-- Update Profile Information Section --}}
            <div class="p-6 shadow-xl sm:p-8 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="max-w-xl">
                    {{-- El partial 'profile.partials.update-profile-information-form'
                         necesita ser estilizado para modo oscuro.
                         Sus elementos internos (labels, inputs, buttons) deben usar
                         clases dark: para colores de texto, bordes, y fondos.
                         Ejemplo: x-input-label, x-text-input, x-primary-button
                    --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Section --}}
            <div class="p-6 shadow-xl sm:p-8 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="max-w-xl">
                    {{-- El partial 'profile.partials.update-password-form' necesita adaptaciones similares para modo oscuro. --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User Section --}}
            @can('manage-roles') {{-- O el permiso apropiado si es para eliminar la propia cuenta y es diferente --}}
            <div class="p-6 shadow-xl sm:p-8 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="max-w-xl">
                    {{-- El partial 'profile.partials.delete-user-form' necesita adaptaciones para modo oscuro,
                         especialmente el botón de peligro (x-danger-button).
                    --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endcan

            {{-- Logout Other Browser Sessions Section (Si tienes este partial desde Jetstream/Breeze) --}}
            {{-- Ejemplo:
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="p-6 shadow-xl sm:p-8 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <div class="max-w-xl">
                        {{-- Este componente Livewire también necesitará estilos dark: --}}
                        {{-- @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                </div>
            @endif
            --}}
        </div>
    </div>

    {{-- Animation Styles (ya presentes) --}}
    <style>
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards;
        }

        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Asegúrate que Font Awesome esté enlazado si usas <i> tags para iconos sociales, e.g., en app.blade.php:
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        */
    </style>
</x-app-layout>
