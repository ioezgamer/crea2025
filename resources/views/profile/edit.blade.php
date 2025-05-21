<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- User Role Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <div class="flex items-center space-x-3 mb-2">
                                <svg class="h-7 w-7 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                                </svg>
                                <h2 class="text-xl font-semibold text-slate-800">
                                    {{ __('Rol de Usuario') }}
                                </h2>
                            </div>
                            <p class="text-sm text-slate-600">
                                {{ __("Este es tu rol actual asignado en el sistema. El rol determina tus permisos y acceso a diferentes funcionalidades.") }}
                            </p>
                        </header>

                        <div class="p-4 bg-slate-100/70 rounded-xl border border-slate-200">
                            <x-input-label for="role_display" :value="__('Rol Asignado')" class="text-sm font-medium text-slate-700 mb-1" />
                            {{-- Displaying role as a styled paragraph for a cleaner look --}}
                            <p id="role_display" class="mt-1 block w-full px-3 py-2.5 text-lg font-medium text-indigo-700 bg-white rounded-lg shadow-sm border border-slate-300">
                                {{ ucfirst($user->role) }}
                            </p>
                            {{-- Alternative: Disabled input for a form-like feel if preferred
                            <x-text-input id="role_display_input" name="role_display_input" type="text" 
                                          class="mt-1 block w-full bg-slate-100 border-slate-300 text-slate-700 cursor-not-allowed rounded-lg shadow-sm text-lg" 
                                          :value="ucfirst($user->role)" readonly disabled />
                            --}}
                        </div>
                         <p class="mt-3 text-xs text-slate-500">
                            {{ __("Nota: El rol de usuario solo puede ser modificado por un administrador del sistema.") }}
                        </p>
                    </section>
                </div>
            </div>

            {{-- Update Profile Information Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- Ensure the partial 'profile.partials.update-profile-information-form' is styled for light theme --}}
                    {{-- It will inherit the card styling from this parent div. --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- Ensure the partial 'profile.partials.update-password-form' is styled for light theme --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User Section --}}
            <div class="p-6 sm:p-8 bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    {{-- Ensure the partial 'profile.partials.delete-user-form' is styled for light theme --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- 
    This is a Blade template for the user profile edit page in a Laravel application.
    It includes sections for displaying the user's role, updating profile information,
    changing the password, and deleting the user account.
    The layout is responsive and uses Tailwind CSS for styling.