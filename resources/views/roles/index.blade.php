<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de roles') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl max-w-9xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status') === 'role-updated')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('Rol actualizado correctamente.') }}
                        </div>
                    @endif
                    @if (session('status') === 'user-deleted')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('Usuario eliminado correctamente.') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->role ?? 'user' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <!-- Update Role Form -->
                                            <form action="{{ route('roles.update', $user) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <select name="role" class="border-gray-300 rounded-md">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ $role }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900">Actualizar</button>
                                            </form>
                                            <!-- Delete User Form (only for non-admin users) -->
                                            @if ($user->role !== 'admin')
                                                <form action="{{ route('roles.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>