<x-app-layout>
    <x-slot name="header">
        {{-- El header ahora es solo informativo, sin botones de acción --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    Directorio de Participantes
                </h1>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">
                    Consulta la información detallada de los miembros del programa.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                {{-- Los filtros ahora apuntan a la nueva ruta 'participante.consulta' --}}
                <div class="px-4 py-4 border-b sm:px-6 border-slate-200 dark:border-slate-700">
                    <form method="GET" action="{{ route('participante.consulta') }}" class="grid items-end grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                        <input type="text" name="search_name" placeholder="Buscar por nombre o apellido..." value="{{ request('search_name') }}" class="w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        <select name="search_programa" class="w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="">Todos los programas</option>
                            @foreach($programas as $programaOption)
                                <option value="{{ $programaOption }}" {{ request('search_programa') == $programaOption ? 'selected' : '' }}>{{ $programaOption }}</option>
                            @endforeach
                        </select>
                        <select name="search_lugar" class="w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                             <option value="">Todos los lugares</option>
                             @foreach($lugarOptions as $lugarOption)
                                <option value="{{ $lugarOption }}" {{ request('search_lugar') == $lugarOption ? 'selected' : '' }}>{{ $lugarOption }}</option>
                            @endforeach
                        </select>
                         <select name="search_grado" class="w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            <option value="">Todos los grados</option>
                            @foreach($gradoOptions as $gradoOption)
                                <option value="{{ $gradoOption }}" {{ request('search_grado') == $gradoOption ? 'selected' : '' }}>{{ $gradoOption }}</option>
                            @endforeach
                        </select>
                        <div class="flex space-x-2">
                            <x-primary-button type="submit" class="w-full"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filtrar</x-primary-button>
                            <x-secondary-button type="button" class="w-full" onclick="window.location.href='{{ route('participante.consulta') }}'">Limpiar</x-secondary-button>
                        </div>
                    </form>
                </div>

                {{-- Tabla de Participantes (solo lectura) --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-100 dark:bg-slate-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Participante</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Grado</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Edad</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Programa(s)</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Tutor Principal</th>
                                <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                            @forelse ($participantes as $participante)
                                <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                    {{-- Columna de Usuario --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- El nombre es un enlace para ver los detalles completos --}}
                                        <a href="{{ route('participante.show', $participante) }}" class="hover:underline">
                                            <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ $participante->primer_nombre_p }} {{ $participante->primer_apellido_p }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $participante->comunidad_p }}</div>
                                        </a>
                                    </td>
                                    {{-- Columna de Grado --}}
                                    <td class="px-6 py-4 text-sm text-left whitespace-nowrap text-slate-600 dark:text-slate-300">
                                        {{ $participante->grado_p ?? 'N/A' }}
                                    </td>
                                    {{-- Columna de Edad --}}
                                    <td class="px-6 py-4 text-sm text-center whitespace-nowrap text-slate-600 dark:text-slate-300">
                                        {{ $participante->edad_p ?? 'N/A' }}
                                    </td>
                                    {{-- Columna de Programas --}}
                                    <td class="px-6 py-4 text-xs whitespace-nowrap text-slate-600 dark:text-slate-300">
                                        {{ str_replace(',', ', ', $participante->programas) }}
                                    </td>
                                    {{-- Columna de Tutor --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $participante->nombres_y_apellidos_tutor_principal }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $participante->telefono_tutor }}</div>
                                    </td>
                                    {{-- Columna de Estado --}}
                                    <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                                        @if($participante->activo)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-700/30 dark:text-green-200">Activo</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-700/30 dark:text-red-200">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm">No se encontraron participantes con los filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginación --}}
                @if ($participantes->hasPages())
                    <div class="px-6 py-4 border-t bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-700 rounded-b-xl">
                        {{ $participantes->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
