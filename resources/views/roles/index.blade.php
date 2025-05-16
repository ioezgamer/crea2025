<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 leading-tight">
                {{ __('Gestión de roles y usuarios') }}
            </h2>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0">
            <h2 class="text-xs lg:text-lg text-blue-600/50 leading-tight mr-4">
                Crear nuevo usuario
            </h2>
             <x-crear-button onclick="window.location.href='{{ route('roles.user.create') }}'"/>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Componente de Alerta (Función PHP para renderizar alertas de sesión) --}}
            @php
            function render_alert($type, $title, $message) {
                $baseClasses = 'p-4 mb-6 rounded-lg shadow-md text-sm border-l-4 flex items-start space-x-3';
                $iconClasses = 'h-5 w-5 flex-shrink-0';
                $textClasses = 'flex-grow';
                $titleClasses = 'font-semibold block';
                $messageClasses = 'text-xs';

                $typeClasses = match ($type) {
                    'success' => 'bg-green-50 border-green-500 text-green-700',
                    'error'   => 'bg-red-50 border-red-500 text-red-700',
                    'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700',
                    'info'    => 'bg-blue-50 border-blue-500 text-blue-700',
                    default   => 'bg-gray-50 border-gray-500 text-gray-700',
                };

                $iconSvg = match ($type) {
                    'success' => '<svg class="'.$iconClasses.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    'error'   => '<svg class="'.$iconClasses.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    'warning' => '<svg class="'.$iconClasses.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
                    'info'    => '<svg class="'.$iconClasses.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                    default   => '',
                };

                return "<div class=\"{$baseClasses} {$typeClasses}\" role=\"alert\">
                            {$iconSvg}
                            <div class=\"{$textClasses}\">
                                <strong class=\"{$titleClasses}\">{$title}</strong>
                                <span class=\"{$messageClasses}\">{$message}</span>
                            </div>
                        </div>";
            }
            @endphp

            {{-- Mensajes de Feedback Usando la Función de Alerta --}}
            @if (session('status') === 'role-updated')
                {!! render_alert('success', '¡Éxito!', __('Rol actualizado correctamente.')) !!}
            @endif
            @if (session('status') === 'user-created')
                {!! render_alert('success', '¡Éxito!', __('Usuario creado correctamente.')) !!}
            @endif
            @if (session('status') === 'user-deleted')
                {!! render_alert('success', '¡Éxito!', __('Usuario eliminado correctamente.')) !!}
            @endif
            @if (session('status') === 'user-approved')
                {!! render_alert('success', '¡Éxito!', __('Usuario aprobado correctamente.')) !!}
            @endif
            @if (session('status') === 'user-approval-revoked')
                {!! render_alert('warning', 'Aviso', __('Se ha revocado la aprobación del usuario.')) !!}
            @endif
            @if (session('error'))
                {!! render_alert('error', 'Error', session('error')) !!}
            @endif
            @if (session('info'))
                {!! render_alert('info', 'Información', session('info')) !!}
            @endif


            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                        <h3 class="text-lg font-semibold text-gray-700">Listado de Usuarios</h3>
                        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <form method="GET" action="{{ route('roles.index') }}" class="flex items-center space-x-2">
                                <select name="approval_status" onchange="this.form.submit()" class="block w-full sm:w-auto border-gray-300 rounded-lg shadow-sm text-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 pl-3 pr-8">
                                    <option value="">Todos los Estados</option>
                                    <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Aprobados</option>
                                    <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                                </select>
                                <div class="relative">
                                    <input type="text" name="search" placeholder="Buscar usuario..."
                                           class="w-full sm:w-56 px-4 py-2.5 border border-gray-300 rounded-lg text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ request('search') }}">
                                    <button type="submit" class="absolute right-0 top-0 bottom-0 my-auto mr-3 text-gray-400 hover:text-indigo-600">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                     <p class="text-xs text-gray-500 mt-1">
                        Administra los roles y el estado de aprobación de los usuarios.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Aprobación</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $role = $user->role ?? 'user';
                                            $roleColor = match (strtolower($role)) {
                                                'admin' => 'bg-red-100 text-red-700',
                                                'editor' => 'bg-yellow-100 text-yellow-700',
                                                'gestor' => 'bg-blue-100 text-blue-700',
                                                'user' => 'bg-gray-100 text-gray-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColor }}">{{ ucfirst($role) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($user->isApproved())
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aprobado
                                            </span>
                                            <span class="block text-xxs text-gray-500 mt-0.5">{{ $user->approved_at->isoFormat('D MMM, YYYY HH:mm') }}</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-1 md:space-x-2">
                                            @if(Auth::user()->id !== $user->id)
                                            <form action="{{ route('roles.update', $user) }}" method="POST" class="flex items-center space-x-1" onsubmit="event.preventDefault(); confirmAction(this, 'Actualizar Rol', '¿Estás seguro de actualizar el rol de este usuario?', 'Sí, actualizar', 'Cancelar', 'indigo');">
                                                @csrf
                                                <select name="role" class="w-auto block border-gray-300 rounded-md shadow-sm text-xs focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-1.5 pl-2 pr-7">
                                                    @foreach ($roles as $roleOption)
                                                        <option value="{{ $roleOption }}" {{ $user->role === $roleOption ? 'selected' : '' }}>{{ ucfirst($roleOption) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-1.5 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500" title="Actualizar rol">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic mr-2">(Tu rol)</span>
                                            @endif

                                            @if(Auth::user()->id !== $user->id)
                                                @if($user->isApproved())
                                                    <form action="{{ route('roles.user.unapprove', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Revocar Aprobación', '¿Estás seguro de revocar la aprobación de este usuario?', 'Sí, revocar', 'Cancelar', 'yellow');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-500" title="Revocar Aprobación">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('roles.user.approve', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Aprobar Usuario', '¿Estás seguro de aprobar a este usuario?', 'Sí, aprobar', 'Cancelar', 'green');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500" title="Aprobar Usuario">
                                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if (Auth::user()->id !== $user->id && $user->role !== 'admin')
                                                <form action="{{ route('roles.destroy', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar Usuario', '¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.', 'Sí, eliminar', 'Cancelar', 'red');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500" title="Eliminar usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm text-gray-500">No se encontraron usuarios.</p>
                                            @if(request('search') || request('approval_status'))
                                                <p class="text-xs text-gray-400 mt-1">Intenta ajustar tus filtros de búsqueda.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $users->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmAction(formElement, title, text, confirmButtonText, cancelButtonText, confirmButtonColor = 'indigo') {
            // Prevenir el envío inmediato del formulario
            // event.preventDefault(); // Ya se hace con onsubmit="event.preventDefault(); ..."

            Swal.fire({
                title: title,
                text: text,
                icon: (confirmButtonColor === 'red' || confirmButtonColor === 'yellow') ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                customClass: {
                    confirmButton: `btn-sweetalert btn-${confirmButtonColor}`,
                    cancelButton: 'btn-sweetalert btn-gray'
                },
                buttonsStyling: false // Usar nuestras propias clases para los botones
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit(); // Enviar el formulario si el usuario confirma
                }
            });
        }
    </script>
    <style>
        /* Estilos personalizados para los botones de SweetAlert2 */
        .btn-sweetalert {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 600; /* font-semibold */
            font-size: 0.875rem; /* text-sm */
            margin: 0 0.25rem;
            transition: background-color 0.15s ease-in-out;
        }
        .btn-sweetalert.btn-indigo {
            background-color: #4f46e5; /* indigo-600 */
            color: white;
        }
        .btn-sweetalert.btn-indigo:hover {
            background-color: #4338ca; /* indigo-700 */
        }
        .btn-sweetalert.btn-red {
            background-color: #dc2626; /* red-600 */
            color: white;
        }
        .btn-sweetalert.btn-red:hover {
            background-color: #b91c1c; /* red-700 */
        }
        .btn-sweetalert.btn-green {
            background-color: #16a34a; /* green-600 */
            color: white;
        }
        .btn-sweetalert.btn-green:hover {
            background-color: #15803d; /* green-700 */
        }
        .btn-sweetalert.btn-yellow {
            background-color: #ca8a04; /* yellow-600 */
            color: white;
        }
        .btn-sweetalert.btn-yellow:hover {
            background-color: #a16207; /* yellow-700 */
        }
        .btn-sweetalert.btn-gray {
            background-color: #6b7280; /* gray-500 */
            color: white;
        }
        .btn-sweetalert.btn-gray:hover {
            background-color: #4b5563; /* gray-600 */
        }
    </style>
</x-app-layout>
