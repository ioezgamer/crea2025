<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    {{ __('Gestión de Usuarios y Roles') }}
                </h1>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">
                    Administra el acceso y los permisos de los usuarios del sistema.
                </p>
            </div>
             {{-- Create User Button --}}
             <a href="{{ route('roles.user.create', request()->query()) }}" title="Añadir usuario"
                class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden text-xs tracking-widest text-white transition-all duration-300 ease-in-out border-2 border-white rounded-full shadow-lg group sm:w-14 sm:h-14 bg-gradient-to-br from-indigo-500 to-purple-600 sm:border-2 dark:border-slate-700 hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                 <svg class="w-5 h-5 text-white transition-transform duration-300 ease-in-out sm:w-6 sm:h-6 group-hover:-translate-y-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></path>
                 </svg>
                 <span class="absolute text-xs font-medium text-white transition-all duration-200 ease-in-out scale-90 opacity-0 translate-x-1/8 whitespace-nowrap sm:text-sm group-hover:opacity-100 group-hover:scale-100">
                     Nuevo usuario
                 </span>
             </a>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="px-4 mx-auto max-w-[180dvh] sm:px-6 lg:px-8">

            {{-- Alertas de Sesión --}}
            <div class="mb-6">
                @if (session('success'))
                    <x-alert type="success" title="¡Éxito!" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" title="Error" :message="session('error')" />
                @endif
                @if (session('info'))
                    <x-alert type="info" title="Información" :message="session('info')" />
                @endif
            </div>

            <div class="overflow-hidden shadow-xl rounded-3xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-xl ">
                {{-- Filtros y Estadísticas --}}
                <div class="px-4 py-4 border-b sm:px-6 border-slate-200 dark:border-slate-700">
                    <form method="GET" action="{{ route('roles.index') }}" class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 ">
                        <input type="text" name="search" placeholder="Buscar por nombre o email..." value="{{ request('search') }}" class="w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">

                        {{-- Custom Select for Approval Status --}}
                        <div x-data="{ open: false, selected: '{{ request('approval_status', '') }}',
                            get text() {
                                if (this.selected === 'approved') return 'Aprobados';
                                if (this.selected === 'pending') return 'Pendientes';
                                return 'Todos los estados';
                            }
                        }" class="relative">
                             <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-900/50 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                <span class="flex items-center"><span class="block truncate" x-text="text"></span></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                <div @click="selected = ''; open = false" :class="{'bg-indigo-600 text-white': selected === ''}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate" :class="{'font-semibold': selected === ''}">Todos los estados</span></div>
                                <div @click="selected = 'approved'; open = false" :class="{'bg-indigo-600 text-white': selected === 'approved'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate" :class="{'font-semibold': selected === 'approved'}">Aprobados</span></div>
                                <div @click="selected = 'pending'; open = false" :class="{'bg-indigo-600 text-white': selected === 'pending'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate" :class="{'font-semibold': selected === 'pending'}">Pendientes</span></div>
                            </div>
                            <select name="approval_status" x-model="selected" class="hidden">
                                <option value="">Todos los estados</option>
                                <option value="approved">Aprobados</option>
                                <option value="pending">Pendientes</option>
                            </select>
                        </div>

                        {{-- Custom Select for Role --}}
                         <div x-data="{ open: false, selected: '{{ request('role', '') }}' }" class="relative">
                             <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-900/50 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                <span class="flex items-center"><span class="block truncate" x-text="selected || 'Todos los roles'"></span></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                <div @click="selected = ''; open = false" :class="{'bg-indigo-600 text-white': selected === ''}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate" :class="{'font-semibold': selected === ''}">Todos los roles</span></div>
                                @foreach($roles as $role)
                                    <div @click="selected = '{{ $role }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $role }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
                                        <span class="block truncate" :class="{'font-semibold': selected === '{{ $role }}'}">{{ $role }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <select name="role" x-model="selected" class="hidden">
                                <option value="">Todos los roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <x-primary-button type="submit" class="w-full"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filtrar</x-primary-button>
                            <x-secondary-button class="w-full" onclick="window.location.href='{{ route('roles.index') }}'"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Limpiar</x-secondary-button>
                        </div>
                    </form>
                    <div class="mt-4 text-xs text-slate-600 dark:text-slate-400">
                        Total: {{ $totalUsers }} | Aprobados: {{ $approvedUsers }} | Pendientes: {{ $pendingUsers }}
                    </div>
                </div>

                {{-- Tabla de Usuarios --}}
                <div class="overflow-x-auto ">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Usuario</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Rol</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Estado</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                            @forelse ($users as $user)
                                <tr class="transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-700/40">
                                    {{-- Columna de Usuario --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                                    </td>
                                    {{-- Columna de Rol --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleName = $user->getRoleNames()->first();
                                            $roleClass = match($roleName) {
                                                'Administrador' => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-900/50 dark:text-indigo-300 dark:border-indigo-700',
                                                'Coordinador' => 'bg-sky-100 text-sky-800 border-sky-300 dark:bg-sky-900/50 dark:text-sky-300 dark:border-sky-700',
                                                'Maestro' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700',
                                                default => 'bg-slate-100 text-slate-700 border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold leading-5 border rounded-full {{ $roleClass }}">
                                            {{ $roleName ?: 'Sin rol' }}
                                        </span>
                                    </td>
                                    {{-- Columna de Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->approved_at)
                                            <div class="flex flex-col">
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 border border-green-300 rounded-full dark:bg-green-900/50 dark:text-green-300 dark:border-green-700">
                                                    Aprobado
                                                </span>
                                                <span class="mt-1 text-xxs text-slate-500 dark:text-slate-400">{{ $user->approved_at->isoFormat('D MMM, YYYY') }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold leading-5 bg-yellow-100 border rounded-full text-yellow-800 border-yellow-300 dark:bg-yellow-900/50 dark:text-yellow-300 dark:border-yellow-700">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Columna de Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1 md:space-x-2">
                                            @if(Auth::user()->id !== $user->id)
                                                {{-- Formulario para Actualizar Rol --}}
                                                <form action="{{ route('roles.update', $user) }}" method="POST" class="flex items-center space-x-1 form-confirm-action"
                                                      data-confirm-title="Actualizar Rol"
                                                      data-confirm-text="¿Estás seguro de cambiar el rol de {{ $user->name }} a un nuevo rol?"
                                                      data-confirm-button-text="Sí, actualizar"
                                                      data-confirm-button-color="indigo">
                                                    @csrf
                                                    <select name="role" class="w-auto block border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-lg shadow-sm text-xs focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-1.5 pl-2 pr-7 transition duration-150 ease-in-out">
                                                        @foreach ($roles as $roleOption)
                                                            <option value="{{ $roleOption }}" {{ $user->hasRole($roleOption) ? 'selected' : '' }}>{{ $roleOption }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 active:scale-90 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-purple-500 shadow-sm hover:shadow-md transition" title="Actualizar rol">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>

                                                {{-- Formulario para Aprobar/Revocar --}}
                                                <form action="{{ $user->approved_at ? route('roles.user.unapprove', $user) : route('roles.user.approve', $user) }}" method="POST" class="form-confirm-action"
                                                      data-confirm-title="{{ $user->approved_at ? 'Revocar Aprobación' : 'Aprobar Usuario' }}"
                                                      data-confirm-text="¿Estás seguro de {{ $user->approved_at ? 'revocar la aprobación de' : 'aprobar a' }} {{ $user->name }}?"
                                                      data-confirm-button-text="{{ $user->approved_at ? 'Sí, revocar' : 'Sí, aprobar' }}"
                                                      data-confirm-button-color="{{ $user->approved_at ? 'yellow' : 'green' }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white active:scale-90 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 shadow-sm hover:shadow-md transition
                                                        {{ $user->approved_at
                                                            ? 'bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:ring-orange-500'
                                                            : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:ring-emerald-500' }}"
                                                        title="{{ $user->approved_at ? 'Revocar Aprobación' : 'Aprobar Usuario' }}">
                                                        @if($user->approved_at)
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        @endif
                                                    </button>
                                                </form>

                                                {{-- No se puede eliminar a un Administrador --}}
                                                @if (!$user->hasRole('Administrador'))
                                                    <form action="{{ route('roles.destroy', $user) }}" method="POST" class="form-confirm-action"
                                                          data-confirm-title="Eliminar Usuario"
                                                          data-confirm-text="¿Estás seguro de eliminar a {{ $user->name }}? Esta acción es irreversible."
                                                          data-confirm-button-text="Sí, Eliminar Permanentemente"
                                                          data-confirm-button-color="red">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 active:scale-90 focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-800 focus:ring-rose-500 shadow-sm hover:shadow-md transition" title="Eliminar usuario">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="px-2 py-1 text-xs italic text-slate-400 dark:text-slate-500">(Eres tú)</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm">No se encontraron usuarios con los filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-700 rounded-b-xl">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        import { confirmAction } from '{{ Vite::asset('resources/js/utils/helpers.js') }}';

        document.querySelectorAll('.form-confirm-action').forEach(form => {
            form.addEventListener('submit', function(event) {
                const title = this.dataset.confirmTitle || '¿Estás seguro?';
                const text = this.dataset.confirmText || 'Esta acción no se puede deshacer.';
                const buttonText = this.dataset.confirmButtonText || 'Sí, continuar';
                const buttonColor = this.dataset.confirmButtonColor || 'indigo';

                confirmAction(event, title, text, buttonText, 'Cancelar', buttonColor);
            });
        });
    </script>
    @endpush
</x-app-layout>
