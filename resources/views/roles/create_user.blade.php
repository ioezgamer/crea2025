{{-- Guardar este archivo como: resources/views/roles/create_user.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                {{ __('Creación de nuevo usuario') }}
            </h1>
            {{-- El botón de regresar ahora apunta a la lista de roles/usuarios --}}
            <x-boton-regresar onclick="window.location.href='{{ route('roles.index') }}'" />
        </div>

    </x-slot>

    <div class="py-8 font-sans from-indigo-600 via-purple-600 to-pink-500">
        <div class="max-w-2xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl rounded-xl">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 sm:px-8">
                    <h1 class="text-2xl font-semibold text-center text-white">Registrar nuevo usuario</h1>
                    <p class="mt-1 text-sm text-center text-indigo-200">Complete la información del nuevo usuario y asigne un rol.</p>
                </div>

                <div class="p-6 sm:p-8">
                    @if (session('success'))
                        <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border-l-4 border-green-500 rounded-md shadow" role="alert">
                            <p class="font-medium">¡Éxito!</p>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 border-l-4 border-red-500 rounded-md shadow" role="alert">
                            <p class="font-bold">Por favor corrige los siguientes errores:</p>
                            <ul class="mt-2 text-xs list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- El action del formulario ahora usa la nueva ruta 'roles.user.store' --}}
                    <form id="createUserForm" action="{{ route('roles.user.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Nombre completo<span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('name') @enderror"
                                   required autocomplete="name">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('email') @enderror"
                                   required autocomplete="email">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Contraseña <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('password') @enderror"
                                   required autocomplete="new-password">
                            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Confirmar Contraseña <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out"
                                   required autocomplete="new-password">
                        </div>

                        <div>
                            <label for="role" class="block mb-1 text-sm font-medium text-gray-700">Asignar Rol <span class="text-red-500">*</span></label>
                            <select name="role" id="role"
                                    class="w-full px-4 py-2.5 dark:text-gray-600 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('role') @enderror"
                                    required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seleccione un rol...</option>
                                @foreach ($roles as $roleOption) {{-- $roles se pasa desde RoleController@create --}}
                                    <option value="{{ $roleOption }}" {{ old('role') == $roleOption ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Checkbox para aprobar inmediatamente --}}
                        <div class="flex items-center">
                            <input id="approve_now" name="approve_now" type="checkbox" value="1" {{ old('approve_now', true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="approve_now" class="block ml-2 text-sm text-gray-900">
                                Aprobar usuario inmediatamente
                            </label>
                        </div>
                        @error('approve_now') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror


                        <div class="flex flex-col items-center justify-end pt-5 space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <x-secondary-button type="button" onclick="document.getElementById('createUserForm').reset();">
                                Limpiar
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                Crear Usuario
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
