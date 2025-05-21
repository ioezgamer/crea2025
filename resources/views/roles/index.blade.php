<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <h2 class="text-xl lg:text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
                {{ __('Gestión de Roles y Usuarios') }}
            </h2>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                <h3 class="text-sm lg:text-base text-slate-700 leading-tight">
                    Crear Nuevo Usuario
                </h3>
                {{-- Assuming x-crear-button is styled according to the CREA theme --}}
                {{-- Example of how it might be styled if it were a simple anchor tag:
                <a href="{{ route('roles.user.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Nuevo
                </a>
                --}}
                <x-crear-button onclick="window.location.href='{{ route('roles.user.create') }}'"/>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alert Component Function --}}
            @php
            function render_alert($type, $title, $message) {
                $baseClasses = 'p-4 mb-6 rounded-xl shadow-lg text-sm border-l-4 flex items-start space-x-3 bg-white/80 backdrop-blur-md'; // Added backdrop
                $iconClasses = 'h-5 w-5 flex-shrink-0 mt-0.5';
                $textClasses = 'flex-grow';
                $titleClasses = 'font-semibold block text-slate-800'; // Adjusted for light theme
                $messageClasses = 'text-xs text-slate-600'; // Adjusted for light theme

                $typeClasses = match ($type) {
                    'success' => 'border-green-500',
                    'error'   => 'border-red-500',
                    'warning' => 'border-amber-500',
                    'info'    => 'border-sky-500',
                    default   => 'border-slate-500',
                };

                $iconColor = match ($type) {
                    'success' => 'text-green-500',
                    'error'   => 'text-red-500',
                    'warning' => 'text-amber-500',
                    'info'    => 'text-sky-500',
                    default   => 'text-slate-500',
                };

                $iconSvg = match ($type) {
                    'success' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    'error'   => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    'warning' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
                    'info'    => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
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

            {{-- Feedback Messages Using Alert Function --}}
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

            {{-- Main Content Card for User List --}}
            <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Listado de Usuarios</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Administra los roles y el estado de aprobación de los usuarios.
                            </p>
                        </div>
                        <form method="GET" action="{{ route('roles.index') }}" class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full md:w-auto">
                            <select name="approval_status" onchange="this.form.submit()" 
                                    class="block w-full sm:w-auto border-slate-300 rounded-xl shadow-sm text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                                <option value="">Todos los Estados</option>
                                <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Aprobados</option>
                                <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                            </select>
                            <div class="relative w-full sm:w-auto">
                                <input type="text" name="search" placeholder="Buscar usuario..."
                                       class="w-full sm:w-56 px-4 py-2.5 border border-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                       value="{{ request('search') }}">
                                <button type="submit" class="absolute right-0 top-0 bottom-0 my-auto mr-3 text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Correo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Estado Aprobación</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-sky-50/70 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $role = $user->role ?? 'user';
                                            $roleColorClasses = match (strtolower($role)) {
                                                'admin' => 'bg-red-100 text-red-700 border-red-300',
                                                'editor' => 'bg-amber-100 text-amber-700 border-amber-300',
                                                'gestor' => 'bg-sky-100 text-sky-700 border-sky-300',
                                                'user' => 'bg-slate-100 text-slate-700 border-slate-300',
                                                default => 'bg-slate-100 text-slate-700 border-slate-300',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $roleColorClasses }}">{{ ucfirst($role) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($user->isApproved())
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-300">
                                                Aprobado
                                            </span>
                                            <span class="block text-xxs text-slate-500 mt-0.5">{{ $user->approved_at->isoFormat('D MMM, YY HH:mm') }}</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-1 md:space-x-2">
                                            @if(Auth::user()->id !== $user->id)
                                            <form action="{{ route('roles.update', $user) }}" method="POST" class="flex items-center space-x-1" onsubmit="event.preventDefault(); confirmAction(this, 'Actualizar Rol', '¿Estás seguro de actualizar el rol de este usuario?', 'Sí, actualizar', 'Cancelar', 'indigo');">
                                                @csrf
                                                <select name="role" class="w-auto block border-slate-300 rounded-lg shadow-sm text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-1.5 pl-2 pr-7 transition duration-150 ease-in-out">
                                                    @foreach ($roles as $roleOption)
                                                        <option value="{{ $roleOption }}" {{ $user->role === $roleOption ? 'selected' : '' }}>{{ ucfirst($roleOption) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-purple-500 shadow-sm hover:shadow-md" title="Actualizar rol">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            @else
                                                <span class="text-xs text-slate-400 italic mr-2">(Tu rol)</span>
                                            @endif

                                            @if(Auth::user()->id !== $user->id)
                                                @if($user->isApproved())
                                                    <form action="{{ route('roles.user.unapprove', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Revocar Aprobación', '¿Estás seguro de revocar la aprobación de este usuario?', 'Sí, revocar', 'Cancelar', 'yellow');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500 shadow-sm hover:shadow-md" title="Revocar Aprobación">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('roles.user.approve', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Aprobar Usuario', '¿Estás seguro de aprobar a este usuario?', 'Sí, aprobar', 'Cancelar', 'green');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500 shadow-sm hover:shadow-md" title="Aprobar Usuario">
                                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if (Auth::user()->id !== $user->id && $user->role !== 'admin')
                                                <form action="{{ route('roles.destroy', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar Usuario', '¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.', 'Sí, eliminar', 'Cancelar', 'red');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-rose-500 shadow-sm hover:shadow-md" title="Eliminar usuario">
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
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm text-slate-500">No se encontraron usuarios.</p>
                                            @if(request('search') || request('approval_status'))
                                                <p class="text-xs text-slate-400 mt-1">Intenta ajustar tus filtros de búsqueda.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 rounded-b-xl">
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
            Swal.fire({
                title: title,
                text: text,
                icon: (confirmButtonColor === 'red' || confirmButtonColor === 'yellow') ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                customClass: {
                    popup: 'rounded-xl shadow-lg', // General popup styling
                    confirmButton: `btn-sweetalert btn-${confirmButtonColor}`,
                    cancelButton: 'btn-sweetalert btn-slate' // Consistent slate for cancel
                },
                buttonsStyling: false 
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit(); 
                }
            });
        }
    </script>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
        /* Custom SweetAlert2 Button Styles for CREA Theme */
        .btn-sweetalert {
            padding: 0.625rem 1.25rem; /* py-2.5 px-5 */
            border-radius: 0.75rem; /* rounded-xl */
            font-weight: 600; /* font-semibold */
            font-size: 0.875rem; /* text-sm */
            margin: 0 0.375rem; /* mx-1.5 */
            transition: all 0.15s ease-in-out;
            border: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-md */
        }
        .btn-sweetalert:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-lg */
        }
        .btn-sweetalert.btn-indigo {
            background-image: linear-gradient(to right, #4f46e5, #8b5cf6); /* from-indigo-600 to-purple-500 */
            color: white;
        }
        .btn-sweetalert.btn-indigo:hover {
            background-image: linear-gradient(to right, #4338ca, #7c3aed); /* from-indigo-700 to-purple-600 */
        }
        .btn-sweetalert.btn-red {
            background-image: linear-gradient(to right, #dc2626, #f43f5e); /* from-red-600 to-rose-500 */
            color: white;
        }
        .btn-sweetalert.btn-red:hover {
            background-image: linear-gradient(to right, #b91c1c, #e11d48); /* from-red-700 to-rose-600 */
        }
        .btn-sweetalert.btn-green {
            background-image: linear-gradient(to right, #16a34a, #10b981); /* from-green-600 to-emerald-500 */
            color: white;
        }
        .btn-sweetalert.btn-green:hover {
            background-image: linear-gradient(to right, #15803d, #059669); /* from-green-700 to-emerald-600 */
        }
        .btn-sweetalert.btn-yellow {
            background-image: linear-gradient(to right, #f59e0b, #f97316); /* from-amber-500 to-orange-500 */
            color: white;
        }
        .btn-sweetalert.btn-yellow:hover {
            background-image: linear-gradient(to right, #d97706, #ea580c); /* from-amber-600 to-orange-600 */
        }
        .btn-sweetalert.btn-slate {
            background-color: #e2e8f0; /* slate-200 */
            color: #334155; /* slate-700 */
        }
        .btn-sweetalert.btn-slate:hover {
            background-color: #cbd5e1; /* slate-300 */
        }

        /* Styling for Laravel Pagination to match the theme */
        .pagination span, .pagination a {
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.5rem; /* rounded-lg */
            font-size: 0.875rem; /* text-sm */
            transition: all 0.15s ease-in-out;
        }
        .pagination span[aria-current="page"] span {
            background-image: linear-gradient(to right, #4f46e5, #8b5cf6);
            color: white;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .pagination a[rel="prev"], .pagination a[rel="next"] {
            color: #4b5563; /* slate-600 */
        }
        .pagination a:hover {
            background-color: #e0e7ff; /* indigo-100 */
            color: #4338ca; /* indigo-700 */
        }
        .pagination span[aria-disabled="true"] span {
            color: #9ca3af; /* slate-400 */
            background-color: #f3f4f6; /* slate-100 */
        }
    </style>
</x-app-layout>
