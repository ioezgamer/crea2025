<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Sección de Rol de Usuario Mejorada --}}
            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <div class="flex items-center space-x-3">
                                {{-- Icono (ejemplo usando Heroicons, asegúrate de tenerlos disponibles o usa SVG directamente) --}}
                                <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-900 ">
                                    {{ __('Rol de usuario') }}
                                </h2>
                            </div>

                            <p class="mt-2 text-sm text-gray-600 ">
                                {{ __("Este es tu rol actual asignado en el sistema. El rol determina tus permisos y acceso a diferentes funcionalidades.") }}
                            </p>
                        </header>

                        <div class="p-4 bg-gray-50  rounded-md">
                            <x-input-label for="role" :value="__('Rol Asignado')" class="text-base font-medium" />
                            <p id="role" class="mt-1 block w-full px-3 py-2 border border-gray-300  rounded-md shadow-sm bg-gray-100  text-gray-700 d text-lg">
                                {{ ucfirst($user->role) }} {{-- ucfirst para capitalizar el rol --}}
                            </p>
                            {{-- Si prefieres un input deshabilitado, puedes usar:
                            <x-text-input id="role_display" name="role_display" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-lg" :value="ucfirst($user->role)" readonly disabled />
                            --}}
                        </div>
                         <p class="mt-2 text-xs text-gray-500 ">
                                {{ __("Nota: El rol de usuario solo puede ser modificado por un administrador del sistema.") }}
                            </p>
                    </section>
                </div>
            </div>

            {{-- Resto de las secciones del perfil --}}
            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white  shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
