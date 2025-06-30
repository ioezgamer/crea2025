<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8 ">
            <div>
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    {{ __('Crear Nuevo Usuario') }}
                </h1>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">
                    Completa los detalles para registrar un nuevo miembro en el sistema.
                </p>
            </div>
            <x-boton-regresar onclick="window.location.href='{{ route('roles.index') }}'" />
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-2xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-zinc-800/15 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl">

                {{-- Encabezado del Formulario dentro de la Tarjeta --}}
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="flex items-center justify-center w-12 h-12 p-1 shadow-lg rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600">
                            <div class="flex items-center justify-center w-full h-full bg-white rounded-3xl dark:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
                                Información del nuevo usuario

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
                            <x-input-label for="name">Nombre completo <span class="text-red-500">*</span></x-input-label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                </div>
                                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej: Juan Pérez" class="pl-10"/>
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email">Correo electrónico <span class="text-red-500">*</span></x-input-label>
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                </div>
                                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@correo.com" class="pl-10"/>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Contraseñas en un Grid --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            {{-- Contraseña --}}
                            <div>
                                <x-input-label for="password">Contraseña <span class="text-red-500">*</span></x-input-label>
                                 <div class="relative mt-2">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    </div>
                                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" class="pl-10"/>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div>
                                <x-input-label for="password_confirmation">Confirmar Contraseña <span class="text-red-500">*</span></x-input-label>
                                <div class="relative mt-2">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    </div>
                                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="pl-10"/>
                                </div>
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
                        <div class="flex flex-col items-center justify-end pt-5 mt-8 space-y-3 border-t sm:flex-row sm:space-y-0 sm:space-x-4 border-white/20 dark:border-white/10">
                            <x-secondary-button type="button" class="w-full transition duration-200 sm:w-auto hover:scale-[1.02] active:scale-95" onclick="document.getElementById('createUserForm').reset();">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Limpiar Campos
                            </x-secondary-button>
                            <x-primary-button type="submit" class="w-full transition duration-200 sm:w-auto hover:scale-[1.02] active:scale-95">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Crear Usuario
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
