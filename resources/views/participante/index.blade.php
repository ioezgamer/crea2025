<x-app-layout>
    

    <div class="py-6 bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Gestión de Participantes</h2>
                    <p class="mt-1 text-sm text-gray-500">Listado completo de participantes registrados</p>
                </div>
                <x-crear-button 
                    onclick="window.location.href='{{ route('participante.create') }}'" >
                </x-crear-button>
            </div>
        </x-slot>
        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <!-- Card Header with Filters -->
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Search Box -->
                        <div class="w-full md:w-1/3">
                            <form method="GET" action="{{ route('participante.index') }}" class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search_name" 
                                    placeholder="Buscar por nombre..." 
                                    value="{{ request('search_name') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </form>
                        </div>
                        
                        <!-- Program Filter -->
                        <div class="w-full md:w-1/3">
                            <form method="GET" action="{{ route('participante.index') }}">
                                <select 
                                    name="search_programa" 
                                    onchange="this.form.submit()"
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Todos los programas</option>
                                    @foreach($programas as $programa)
                                        <option value="{{ $programa }}" {{ request('search_programa') == $programa ? 'selected' : '' }}>
                                            {{ $programa }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        
                        <!-- Place Filter (will be dynamic) -->
                        <div class="w-full md:w-1/3">
                        </div>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    @if($participantes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No se encontraron participantes</h3>
                            <p class="mt-1 text-sm text-gray-500">Intenta ajustar tus criterios de búsqueda o crea un nuevo participante.</p>
                            <div class="mt-6">
                                <button 
                                    type="button"
                                    onclick="window.location.href='{{ route('participante.create') }}'"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nuevo Participante
                                </button>
                            </div>
                        </div>
                    @else
                    <!-- Texto de información -->
        <!-- Paginación Mejorada -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-3 bg-white border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Mostrando <span class="font-medium">{{ $participantes->firstItem() }}</span> a <span class="font-medium">{{ $participantes->lastItem() }}</span> de <span class="font-medium">{{ $participantes->total() }}</span> resultados
            </div>
            
            <div class="flex items-center space-x-2">
                @if ($participantes->onFirstPage())
                    <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        Anterior
                    </span>
                @else
                    <a href="{{ $participantes->previousPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Anterior
                    </a>
                @endif
        
                @if ($participantes->hasMorePages())
                    <a href="{{ $participantes->nextPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Siguiente
                    </a>
                @else
                    <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        Siguiente
                    </span>
                @endif
            </div>
        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participante</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistencia</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lugar</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                        @can('manage-roles')
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($participantes as $participante)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $participante->participante_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('participante.show', $participante) }}" 
                                                       class="flex items-center hover:text-blue-600 transition-colors">
                                                        {{ $participante->primer_nombre_p ?? 'N/A' }} {{ $participante->segundo_nombre_p ?? '' }} 
                                                        {{ $participante->primer_apellido_p ?? 'N/A' }} {{ $participante->segundo_apellido_p ?? '' }}
                                                        <svg class="w-4 h-4 ml-1 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $participante->comunidad_p ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($participante->dias_de_asistencia_al_programa)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold text-green-700">
                                                        {{ $participante->dias_de_asistencia_al_programa }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $participante->grado_p ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $participante->edad_p ?? 'N/A' }} años
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $programa = $participante->programa ?? 'N/A';
                                                    $colorClasses = match($programa) {
                                                        'Exito Academico' => 'text-blue-700 ',
                                                        'Desarrollo Juvenil' => 'text-teal-600',
                                                        'Biblioteca' => 'text-purple-700',
                                                        default => 'text-gray-800 bg-gray-100'
                                                    };
                                                @endphp
                                                
                                                <span class="px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $colorClasses }}">
                                                    {{ $programa }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @can('manage-roles')
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <a 
                                                            href="{{ route('participante.edit', $participante) }}" 
                                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded-full hover:bg-yellow-50"
                                                            title="Editar"
                                                        >
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('participante.destroy', $participante) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button 
                                                                type="submit"
                                                                class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50"
                                                                title="Eliminar"
                                                                onclick="return confirm('¿Estás seguro de eliminar este participante?')"
                                                            >
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación Mejorada -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-3 bg-white border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                Mostrando <span class="font-medium">{{ $participantes->firstItem() }}</span> a <span class="font-medium">{{ $participantes->lastItem() }}</span> de <span class="font-medium">{{ $participantes->total() }}</span> resultados
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if ($participantes->onFirstPage())
                                    <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                        Anterior
                                    </span>
                                @else
                                    <a href="{{ $participantes->previousPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        Anterior
                                    </a>
                                @endif
                        
                                @if ($participantes->hasMorePages())
                                    <a href="{{ $participantes->nextPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        Siguiente
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                        Siguiente
                                    </span>
                                @endif
                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>