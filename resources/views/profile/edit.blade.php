<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alert for session status --}}
            @if (session('status'))
                <div class="bg-green-50/80 backdrop-blur-md border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-lg relative animate-fade-in-down" role="alert">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 flex-shrink-0 mt-0.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong class="font-bold text-green-800">¡Éxito!</strong>
                            <span class="block sm:inline text-sm text-green-700">{{ session('status') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Avatar + Role Section --}}
            <div class=" p-6  sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl mx-auto text-center space-y-6">
                    {{-- Avatar --}}
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8b5cf6&color=fff&rounded=true&size=120&font-size=0.40&bold=true" 
                         alt="Avatar de {{ Auth::user()->name }}" 
                         class="mx-auto h-28 w-28 sm:h-32 sm:w-32 rounded-full shadow-lg border-4 border-white ring-2 ring-purple-500/50">
                    
                    <div class="mt-4">
                        <h1 class="text-3xl sm:text-4xl font-bold text-slate-800">{{ Auth::user()->name }}</h1>
                        <p class="text-sm text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- User Role --}}
                    <div class="p-4 sm:p-6 bg-slate-100/70 rounded-xl border border-slate-200/80 shadow-inner">
                        <x-input-label for="role_display" :value="__('Rol Asignado')" class="text-sm font-medium text-slate-700 mb-1 text-left" />
                        <p id="role_display" class="mt-1 block w-full px-3 py-3 text-lg font-semibold text-indigo-700 bg-white rounded-lg shadow-sm border border-slate-300 text-center">
                            {{ ucfirst($user->role) }}
                        </p>
                        <p class="mt-3 text-xs text-slate-500 text-left">
                            {{ __('Nota: El rol de usuario solo puede ser modificado por un administrador del sistema.') }}
                        </p>
                    </div>

                    {{-- 
                    <div class="flex justify-center space-x-6 mt-6">
                        <a href="#" class="text-indigo-600 hover:text-indigo-500 transition transform hover:scale-110" title="Facebook">
                            <i class="fab fa-facebook-f fa-lg"></i>
                        </a>
                        <a href="#" class="text-sky-500 hover:text-sky-400 transition transform hover:scale-110" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-pink-600 hover:text-pink-500 transition transform hover:scale-110" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-blue-700 hover:text-blue-600 transition transform hover:scale-110" title="LinkedIn">
                            <i class="fab fa-linkedin-in fa-lg"></i>
                        </a>
                    </div>
                     <div class="mt-4 text-xs text-slate-400">
                        
                    </div>
                    (Redes sociales podrían ir aquí si se implementan) --}}
                </div>
            </div>

            {{-- Update Profile Information Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- The partial 'profile.partials.update-profile-information-form' 
                         needs to be styled for the light theme. 
                         Its internal elements (labels, inputs, buttons) should use
                         slate colors for text, indigo/purple for accents, and rounded-xl for inputs/buttons.
                    --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- The partial 'profile.partials.update-password-form' needs similar styling updates. --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User Section --}}
            @can('manage-roles') {{-- Or appropriate permission for deleting own account if different --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- The partial 'profile.partials.delete-user-form' needs similar styling updates,
                         especially the danger button.
                    --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endcan
            
            {{-- Logout Other Browser Sessions Section (If you have this partial from Jetstream/Breeze) --}}
            {{-- Example:
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                    <div class="max-w-xl">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                </div>
            @endif
            --}}
        </div>
    </div>

    {{-- Animation Styles (already present) --}}
    <style>
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards; /* Added forwards to keep final state */
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
        /* Ensure Font Awesome is linked if using i tags for social icons, e.g., in app.blade.php:
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        */
    </style>
</x-app-layout>
