<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    {{ __('Crear Nuevo Usuario') }}
                </h1>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">
                    Completa los detalles para registrar un nuevo miembro en el sistema.
                </p>
            </div>
            <x-boton-regresar href="{{ route('roles.index') }}" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl">

                {{-- Encabezado del Formulario dentro de la Tarjeta --}}
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-12 h-12 p-1 rounded-full shadow-lg bg-gradient-to-br from-indigo-500 to-purple-600">
                            <div class="flex items-center justify-center w-full h-full bg-white rounded-full dark:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                                Información del Nuevo Usuario
                            </h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Los campos con <span class="text-red-500">*</span> son obligatorios.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:px-8">
                    <form id="createUserForm" method="POST" action="{{ route('roles.user.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nombre --}}
                        <div>
                            <x-input-label for="name">
                                Nombre Completo <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej: Juan Pérez" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@correo.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Contraseñas en un Grid --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            {{-- Contraseña --}}
                            <div>
                                <x-input-label for="password">
                                    Contraseña <span class="text-red-500">*</span>
                                </x-input-label>
                                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div>
                                <x-input-label for="password_confirmation">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </x-input-label>
                                <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Línea divisoria --}}
                        <div class="border-t border-slate-200 dark:border-slate-700"></div>

                        {{-- Sección de Rol y Aprobación --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            {{-- Rol (Spatie) --}}
                            <div>
                                <x-input-label for="role">
                                    Asignar Rol <span class="text-red-500">*</span>
                                </x-input-label>
                                {{-- El estilo del select se alinea con los text-input --}}
                                <select id="role" name="role" class="block w-full mt-1 text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="" disabled selected>Seleccione un Rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            {{-- Checkbox para aprobar inmediatamente --}}
                            <div class="flex items-end pb-1 sm:justify-center">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-6">
                                        <input id="approve_now" name="approve_now" type="checkbox" value="1" {{ old('approve_now', true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="approve_now" class="font-medium text-slate-800 dark:text-slate-200">
                                            Aprobar usuario
                                        </label>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Permite el acceso inmediato.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('approve_now')" class="mt-2" />

                        {{-- Botones de Acción --}}
                        <div class="flex flex-col items-center justify-end pt-5 mt-8 space-y-3 border-t sm:flex-row sm:space-y-0 sm:space-x-4 border-slate-200 dark:border-slate-700">
                            <x-secondary-button type="button" class="w-full sm:w-auto" onclick="document.getElementById('createUserForm').reset();">
                                Limpiar Campos
                            </x-secondary-button>
                            <x-primary-button type="submit" class="w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Crear Usuario
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
