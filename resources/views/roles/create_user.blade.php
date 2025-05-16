{{-- Guardar este archivo como: resources/views/roles/create_user.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 leading-tight">
                {{ __('Crear Nuevo Usuario') }}
            </h2>
            {{-- El botón de regresar ahora apunta a la lista de roles/usuarios --}}
            <x-boton-regresar onclick="window.location.href='{{ route('roles.index') }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-5 bg-indigo-700 sm:px-8">
                    <h1 class="text-2xl font-semibold text-white text-center">Registrar Nuevo Usuario</h1>
                    <p class="text-sm text-indigo-200 text-center mt-1">Complete la información del nuevo usuario y asigne un rol.</p>
                </div>

                <div class="p-6 sm:p-8">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 text-sm shadow" role="alert">
                            <p class="font-medium">¡Éxito!</p>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 text-sm shadow" role="alert">
                            <p class="font-bold">Por favor corrige los siguientes errores:</p>
                            <ul class="mt-2 list-disc list-inside text-xs">
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
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('name') border-red-500 @enderror"
                                   required autocomplete="name">
                            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                                   required autocomplete="email">
                            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                                   required autocomplete="new-password">
                            @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out"
                                   required autocomplete="new-password">
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Asignar Rol <span class="text-red-500">*</span></label>
                            <select name="role" id="role" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('role') border-red-500 @enderror" 
                                    required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seleccione un rol...</option>
                                @foreach ($roles as $roleOption) {{-- $roles se pasa desde RoleController@create --}}
                                    <option value="{{ $roleOption }}" {{ old('role') == $roleOption ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Checkbox para aprobar inmediatamente --}}
                        <div class="flex items-center">
                            <input id="approve_now" name="approve_now" type="checkbox" value="1" {{ old('approve_now', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="approve_now" class="ml-2 block text-sm text-gray-900">
                                Aprobar usuario inmediatamente
                            </label>
                        </div>
                        @error('approve_now') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror


                        <div class="pt-5 flex flex-col sm:flex-row justify-end items-center space-y-3 sm:space-y-0 sm:space-x-4">
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
