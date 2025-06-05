<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Título -->
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                {{ __('Gestión de roles y usuarios') }}
            </h1>
            <!-- Botón -->
            <div class="ml-auto">
                <x-boton-crear-usuario onclick="window.location.href='{{ route('roles.user.create') }}'" />
            </div>
        </div>
    </x-slot>
    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">

            {{-- Alert Component Function --}}
            @php
            function render_alert($type, $title, $message) {
                $isDark = Illuminate\Support\Facades\Request::hasCookie('darkMode') && Illuminate\Support\Facades\Request::cookie('darkMode') === 'true'; // Helper to check dark mode, or use a global helper

                $baseClasses = 'p-4 mb-6 rounded-xl shadow-lg text-sm border-l-4 flex items-start space-x-3 backdrop-blur-md';
                $iconClasses = 'h-5 w-5 flex-shrink-0 mt-0.5';
                $textClasses = 'flex-grow';

                // Light theme text colors
                $titleClassesLight = 'font-semibold block text-slate-800';
                $messageClassesLight = 'text-xs text-slate-600';
                // Dark theme text colors
                $titleClassesDark = 'font-semibold block text-slate-100';
                $messageClassesDark = 'text-xs text-slate-300';

                $titleClasses = $isDark ? $titleClassesDark : $titleClassesLight;
                $messageClasses = $isDark ? $messageClassesDark : $messageClassesLight;

                $typeClasses = match ($type) {
                    'success' => 'border-green-500 dark:border-green-600',
                    'error'   => 'border-red-500 dark:border-red-600',
                    'warning' => 'border-amber-500 dark:border-amber-600',
                    'info'    => 'border-sky-500 dark:border-sky-600',
                    default   => 'border-slate-500 dark:border-slate-600',
                };

                // Backgrounds for dark mode
                $bgClasses = match($type) {
                    'success' => 'bg-green-50/80 dark:bg-green-700/30',
                    'error'   => 'bg-red-50/80 dark:bg-red-700/30',
                    'warning' => 'bg-amber-50/80 dark:bg-amber-700/30',
                    'info'    => 'bg-sky-50/80 dark:bg-sky-700/30',
                    default   => 'bg-white/80 dark:bg-slate-700/30',
                };


                $iconColor = match ($type) {
                    'success' => 'text-green-500 dark:text-green-400',
                    'error'   => 'text-red-500 dark:text-red-400',
                    'warning' => 'text-amber-500 dark:text-amber-400',
                    'info'    => 'text-sky-500 dark:text-sky-400',
                    default   => 'text-slate-500 dark:text-slate-400',
                };

                $iconSvg = match ($type) {
                    'success' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    'error'   => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    'warning' => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
                    'info'    => '<svg class="'.$iconClasses.' '.$iconColor.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                    default   => '',
                };

                return "<div class=\"{$baseClasses} {$typeClasses} {$bgClasses}\" role=\"alert\">
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
            <div class="overflow-hidden shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="px-4 py-5 border-b sm:px-6 border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Listado de Usuarios</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Administra los roles y el estado de aprobación de los usuarios.
                            </p>
                        </div>
                        <form method="GET" action="{{ route('roles.index') }}" class="flex flex-col items-center w-full space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 md:w-auto">
                            <select name="approval_status" onchange="this.form.submit()"
                                    class="block w-full sm:w-auto border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm text-xs focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                                <option value="">Todos los Estados</option>
                                <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Aprobados</option>
                                <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                            </select>
                            <div class="relative w-full sm:w-auto">
                                <input type="text" name="search" placeholder="Buscar usuario..."
                                       class="w-full sm:w-56 px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 transition duration-150 ease-in-out"
                                       value="{{ request('search') }}">
                                <button type="submit" class="absolute top-0 bottom-0 right-0 my-auto mr-3 transition-colors text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-100 dark:bg-slate-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Correo</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Rol</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Estado Aprobación</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                            @forelse ($users as $user)
                                <tr class="transition-colors duration-150 hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">ID: {{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $role = $user->role ?? 'user';
                                            $roleColorClasses = match (strtolower($role)) {
                                                'admin' => 'bg-red-100 text-red-700 border-red-300 dark:bg-red-700/30 dark:text-red-200 dark:border-red-500',
                                                'editor' => 'bg-amber-100 text-amber-700 border-amber-300 dark:bg-amber-700/30 dark:text-amber-200 dark:border-amber-500',
                                                'gestor' => 'bg-sky-100 text-sky-700 border-sky-300 dark:bg-sky-700/30 dark:text-sky-200 dark:border-sky-500',
                                                'user' => 'bg-slate-100 text-slate-700 border-slate-300 dark:bg-slate-600/30 dark:text-slate-200 dark:border-slate-500',
                                                default => 'bg-slate-100 text-slate-700 border-slate-300 dark:bg-slate-600/30 dark:text-slate-200 dark:border-slate-500',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $roleColorClasses }}">{{ ucfirst($role) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        @if($user->isApproved())
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-300 dark:bg-green-700/30 dark:text-green-200 dark:border-green-500">
                                                Aprobado
                                            </span>
                                            <span class="block text-xxs text-slate-500 dark:text-slate-400 mt-0.5">{{ $user->approved_at->isoFormat('D MMM, YY HH:mm') }}</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300 dark:bg-yellow-700/30 dark:text-yellow-200 dark:border-yellow-500">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1 md:space-x-2">
                                            @if(Auth::user()->id !== $user->id)
                                            <form action="{{ route('roles.update', $user) }}" method="POST" class="flex items-center space-x-1" onsubmit="event.preventDefault(); confirmAction(this, 'Actualizar Rol', '¿Estás seguro de actualizar el rol de este usuario?', 'Sí, actualizar', 'Cancelar', 'indigo');">
                                                @csrf
                                                <select name="role" class="w-auto block border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-lg shadow-sm text-xs focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-1.5 pl-2 pr-7 transition duration-150 ease-in-out">
                                                    @foreach ($roles as $roleOption)
                                                        <option value="{{ $roleOption }}" {{ $user->role === $roleOption ? 'selected' : '' }}>{{ ucfirst($roleOption) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-purple-500 shadow-sm hover:shadow-md" title="Actualizar rol">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            @else
                                                <span class="mr-2 text-xs italic text-slate-400 dark:text-slate-500">(Tu rol)</span>
                                            @endif

                                            @if(Auth::user()->id !== $user->id)
                                                @if($user->isApproved())
                                                    <form action="{{ route('roles.user.unapprove', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Revocar Aprobación', '¿Estás seguro de revocar la aprobación de este usuario?', 'Sí, revocar', 'Cancelar', 'yellow');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-orange-500 shadow-sm hover:shadow-md" title="Revocar Aprobación">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('roles.user.approve', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Aprobar Usuario', '¿Estás seguro de aprobar a este usuario?', 'Sí, aprobar', 'Cancelar', 'green');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-emerald-500 shadow-sm hover:shadow-md" title="Aprobar Usuario">
                                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif

                                            @if (Auth::user()->id !== $user->id && $user->role !== 'admin')
                                                <form action="{{ route('roles.destroy', $user) }}" method="POST" onsubmit="event.preventDefault(); confirmAction(this, 'Eliminar Usuario', '¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.', 'Sí, eliminar', 'Cancelar', 'red');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-rose-500 shadow-sm hover:shadow-md" title="Eliminar usuario">
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
                                            <svg class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron usuarios.</p>
                                            @if(request('search') || request('approval_status'))
                                                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Intenta ajustar tus filtros de búsqueda.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-700 rounded-b-xl">
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
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                text: text,
                icon: (confirmButtonColor === 'red' || confirmButtonColor === 'yellow') ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                customClass: {
                    popup: `rounded-xl shadow-lg ${isDark ? 'bg-slate-800 text-slate-200' : 'bg-white'}`,
                    title: `${isDark ? 'text-slate-100' : 'text-slate-800'}`,
                    htmlContainer: `${isDark ? 'text-slate-300' : 'text-slate-600'}`,
                    confirmButton: `btn-sweetalert btn-${confirmButtonColor} ${isDark ? 'dark-btn-' + confirmButtonColor : ''}`,
                    cancelButton: `btn-sweetalert btn-slate ${isDark ? 'dark-btn-slate' : ''}`
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
        /* Dark mode SweetAlert button overrides */
        .dark .btn-sweetalert.btn-slate {
            background-color: #475569; /* slate-600 */
            color: #e2e8f0; /* slate-200 */
        }
        .dark .btn-sweetalert.btn-slate:hover {
            background-color: #334155; /* slate-700 */
        }
        /* Dark mode pagination (tailwind.config.js should have darkMode: 'class') */
        .dark .pagination span[aria-current="page"] span {
            background-image: linear-gradient(to right, #6366f1, #a78bfa); /* indigo-500 to purple-400 */
            color: white;
        }
        .dark .pagination a[rel="prev"], .dark .pagination a[rel="next"] {
            color: #9ca3af; /* slate-400 */
        }
        .dark .pagination a:hover {
            background-color: #374151; /* slate-700 */
            color: #d1d5db; /* slate-300 */
        }
        .dark .pagination span[aria-disabled="true"] span {
            color: #6b7280; /* slate-500 */
            background-color: #374151; /* slate-700 */
        }
        /* Custom scrollbar for Webkit browsers */
         .overflow-x-auto::-webkit-scrollbar { height: 8px; }
         .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
         .dark .overflow-x-auto::-webkit-scrollbar-track { background: #334155; } /* slate-700 */
         .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
         .dark .overflow-x-auto::-webkit-scrollbar-thumb { background: #475569; } /* slate-600 */
         .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
         .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #64748b; } /* slate-500 */
    </style>
</x-app-layout>
