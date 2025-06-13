<x-app-layout>
    <x-slot name="header">
        {{-- Header Section with Title and Action Buttons --}}
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    Gestión de Participantes
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    @if(request('grado'))
                        Participantes en el grado: <span class="font-semibold">{{ urldecode(request('grado')) }}</span>
                    @elseif(request('search_name') || request('search_programa') || request('search_lugar'))
                        Resultados de la búsqueda
                    @else
                        Listado completo de participantes
                    @endif
                    <span class="text-xs text-slate-500 dark:text-slate-400"> (Total: {{ $participantes->total() }})</span>
                </p>
            </div>

        </div>
    </x-slot>
    <div class="flex items-center justify-end px-6 pt-4 sm:px-6 lg:px-6">
        <div class="flex items-center space-x-2 sm:space-x-3">
                {{-- Import Button --}}
                <a href="{{ route('participantes.import.form') }}" title="Importar Participantes"
                   class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden text-xs tracking-widest text-white transition-all duration-300 ease-in-out border-white rounded-full shadow-md border-1 group sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 sm:border-2 dark:border-slate-700 hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                    <svg class="w-5 h-5 transition-transform duration-300 ease-in-out sm:w-6 sm:h-6 fill-white group-hover:-translate-y-10" viewBox="0 0 384 512">
                        <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path>
                    </svg>
                    <span class="absolute text-xs font-medium text-white transition-all duration-200 ease-in-out scale-90 opacity-0 whitespace-nowrap sm:text-sm group-hover:opacity-100 group-hover:scale-100">
                        Importar
                    </span>
                </a>

                {{-- Export Button --}}
                <a href="{{ route('participantes.export', request()->query()) }}" title="Exportar Participantes Filtrados"
                   class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden text-xs tracking-widest text-white transition-all duration-300 ease-in-out border-2 border-white rounded-full shadow-lg group sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-emerald-600 sm:border-2 dark:border-slate-700 hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                    <svg class="w-5 h-5 text-white transition-transform duration-300 ease-in-out sm:w-6 sm:h-6 group-hover:-translate-y-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="absolute text-xs font-medium text-white transition-all duration-200 ease-in-out scale-90 opacity-0 whitespace-nowrap sm:text-sm group-hover:opacity-100 group-hover:scale-100">
                        Exportar
                    </span>
                </a>

                {{-- Create Participant Button (x-crear-button should ideally handle its own dark mode variants) --}}
                <a href="{{ route('participante.create', request()->query()) }}" title="Crear Participante"
                   class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden text-xs tracking-widest text-white transition-all duration-300 ease-in-out border-2 border-white rounded-full shadow-lg group sm:w-14 sm:h-14 bg-gradient-to-br from-blue-500 to-violet-600 sm:border-2 dark:border-slate-700 hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                    <svg class="w-5 h-5 text-white transition-transform duration-300 ease-in-out sm:w-6 sm:h-6 group-hover:-translate-y-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></path>
                    </svg>
                    <span class="absolute text-xs font-medium text-white transition-all duration-200 ease-in-out scale-90 opacity-0 translate-x-1/8 whitespace-nowrap sm:text-sm group-hover:opacity-100 group-hover:scale-100">
                        Nuevo participante
                    </span>
                </a>
            </div>
    </div>
    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-full px-2 mx-auto sm:px-4 lg:px-6">
            <div class="overflow-hidden shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                {{-- Filter Form Section --}}
                <div class="px-4 py-4 border-b sm:px-6 border-slate-200 dark:border-slate-700">
                    <form method="GET" action="{{ request('grado') ? route('participante.indexByGrade', ['grado' => request('grado')]) : route('participante.index') }}" id="filterForm" class="space-y-4 md:space-y-0 md:grid md:grid-cols-12 md:gap-4 md:items-end">

                        @if(request('grado') && !request()->routeIs('participante.index'))
                            <input type="hidden" name="grado" value="{{ request('grado') }}">
                        @endif

                        <div class="col-span-12 md:col-span-4 lg:col-span-3">
                            <label for="search_name" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Buscar por Nombre/Apellido</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                </div>
                                <input type="text" name="search_name" id="search_name" placeholder="Nombre o apellido..." value="{{ request('search_name') }}"
                                       class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 transition duration-150 ease-in-out">
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-4 lg:col-span-2">
                            <label for="search_programa" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Programa</label>
                            <select name="search_programa" id="search_programa"
                                    class="mt-1 block w-full pl-3 pr-8 py-2.5 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 transition duration-150 ease-in-out">
                                <option value="">Todos los programas</option>
                                @foreach($programas as $programaOption)
                                    <option value="{{ $programaOption }}" {{ request('search_programa') == $programaOption ? 'selected' : '' }}>
                                        {{ $programaOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-4 lg:col-span-2">
                            <label for="search_lugar" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Lugar de Encuentro</label>
                            <select name="search_lugar" id="search_lugar"
                                    class="mt-1 block w-full pl-3 pr-8 py-2.5 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 transition duration-150 ease-in-out"
                                    {{ request('search_programa') ? '' : 'disabled' }}>
                                <option value="">{{ request('search_programa') ? 'Todos los lugares' : 'Seleccione programa primero' }}</option>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4 lg:col-span-2">
                            <label for="search_grado" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Grado</label>
                            <select name="search_grado" id="search_grado"
                                    class="mt-1 block w-full pl-3 pr-8 py-2.5 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 transition duration-150 ease-in-out"
                                    {{ (request('search_programa') && request('search_lugar')) || (request('search_programa') && !$gradoOptions) ? '' : 'disabled' }}>
                                <option value="">{{ (request('search_programa') && request('search_lugar')) || (request('search_programa') && !$gradoOptions) ? 'Todos los grados' : 'Seleccione programa/lugar' }}</option>
                                @foreach($gradoOptions as $gradoOption)
                                    <option value="{{ $gradoOption }}" {{ request('search_grado') == $gradoOption ? 'selected' : '' }}>
                                        {{ $gradoOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end col-span-12 space-x-2 md:col-span-12 lg:col-span-3"> {{-- Adjusted span for new filter --}}
                            <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Filtrar
                            </button>
                            <a href="{{ route('participante.index') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-slate-200 dark:bg-slate-600 border border-transparent rounded-xl font-semibold text-xs text-slate-700 dark:text-slate-200 uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150 shadow-sm hover:shadow-md" title="Limpiar filtros">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                {{-- global_feedback_toast div is removed. notifications.js will create global_toast_container if needed. --}}

                <div class="p-2 sm:p-4">
                    @if($participantes->isEmpty())
                        <div class="py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-3 text-lg font-semibold text-slate-800 dark:text-slate-200">No se encontraron participantes</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Intenta ajustar tus criterios de búsqueda o crea un nuevo participante.</p>
                            <div class="mt-6">
                                <x-crear-button onclick="window.location.href='{{ route('participante.create') }}'">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Nuevo Participante
                                </x-crear-button>
                            </div>
                        </div>
                    @else
                        <div class="px-2 mb-4 text-xs text-slate-600 dark:text-slate-400">
                            Mostrando <span class="font-semibold">{{ $participantes->firstItem() }}</span> a <span class="font-semibold">{{ $participantes->lastItem() }}</span> de <span class="font-semibold">{{ $participantes->total() }}</span> resultados.
                        </div>
                        <div class="overflow-x-auto border shadow-sm rounded-xl border-slate-200 dark:border-slate-700">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-100 dark:bg-slate-700/50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">ID</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Participante</th>
                                        <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Días Asist.</th>
                                        <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Grado</th>
                                        <th scope="col" class="px-3 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Edad</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Programa(s)</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Lugar</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-left uppercase text-slate-600 dark:text-slate-300">Tutor</th>
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Activo</th>
                                        @can('manage-roles')
                                        <th scope="col" class="px-4 py-3 text-xs font-semibold tracking-wider text-center uppercase text-slate-600 dark:text-slate-300">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                    @foreach ($participantes as $participante)
                                        <tr class="transition-colors duration-150 hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                            <td class="px-4 py-3 text-xs font-medium whitespace-nowrap text-slate-700 dark:text-slate-300">
                                                #{{ $participante->participante_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                                    <a href="{{ route('participante.show', $participante) }}" class="hover:text-indigo-700 dark:hover:text-indigo-400 hover:underline">
                                                        {{ $participante->primer_nombre_p ?? 'N/A' }} {{ $participante->segundo_nombre_p ?? '' }}
                                                        {{ $participante->primer_apellido_p ?? 'N/A' }} {{ $participante->segundo_apellido_p ?? '' }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $participante->comunidad_p ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-3 text-xs whitespace-nowrap text-slate-500 dark:text-slate-400">
                                                @if($participante->dias_de_asistencia_al_programa)
                                                    @php $dias = explode(',', $participante->dias_de_asistencia_al_programa); @endphp
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($dias as $dia)
                                                            <span class="px-1.5 py-0.5 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-full text-xxs font-medium">{{ trim($dia) }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-sm whitespace-nowrap text-slate-600 dark:text-slate-300">
                                                {{ $participante->grado_p == 12 ? 'Adulto' : ($participante->grado_p ?? 'N/A') }}
                                            </td>

                                            <td class="px-3 py-3 text-sm whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $participante->edad_p ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-xs whitespace-nowrap text-slate-600 dark:text-slate-300">
                                                @if($participante->programa)
                                                    @php $programasPrincipales = explode(',', $participante->programa); @endphp
                                                    @foreach($programasPrincipales as $prog)
                                                        <span class="block mb-0.5 px-2 py-0.5 bg-blue-100 dark:bg-blue-700/50 text-blue-700 dark:text-blue-200 rounded-full text-xxs font-semibold">{{ trim($prog) }}</span>
                                                    @endforeach
                                                @endif
                                                @if($participante->programas)
                                                    @php $subProgramas = explode(',', $participante->programas); @endphp
                                                    @foreach($subProgramas as $subProg)
                                                        <span class="block mt-0.5 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-700/50 text-indigo-700 dark:text-indigo-200 rounded-full text-xxs font-semibold">{{ trim($subProg) }}</span>
                                                    @endforeach
                                                @endif
                                                @if(!$participante->programa && !$participante->programas)
                                                    <span class="text-slate-400 dark:text-slate-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                @can('manage-roles')
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer toggle-activo"
                                                               data-participante-id="{{ $participante->participante_id }}"
                                                               {{ $participante->activo ? 'checked' : '' }}>
                                                        <div class="w-9 h-5 bg-slate-200 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-500 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600 dark:peer-checked:bg-indigo-500"></div>
                                                    </label>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participante->activo ? 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-700/30 dark:text-red-200' }}">
                                                        {{ $participante->activo ? 'Sí' : 'No' }}
                                                    </span>
                                                @endcan
                                            </td>
                                            @can('manage-roles')
                                            <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap">
                                                <div class="flex items-center justify-center space-x-1">
                                                    <a href="{{ route('participante.edit', $participante) }}" class="text-amber-500 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 p-1.5 rounded-full hover:bg-amber-100 dark:hover:bg-amber-700/30 transition-colors" title="Editar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <form action="{{ route('participante.destroy', $participante) }}" method="POST" class="inline form-delete-participante"
                                                          data-participante-nombre="{{ $participante->primer_nombre_p }} {{ $participante->primer_apellido_p }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 p-1.5 rounded-full hover:bg-red-100 dark:hover:bg-red-700/30 transition-colors" title="Eliminar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-4 py-3 bg-white border-t dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-b-xl">
                            {{ $participantes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Div para configuración de JS y pasar datos desde el backend --}}
    <div id="participanteIndexConfig" class="hidden"
         data-ruta-lugares-por-programa="{{ route('participante.lugaresPorPrograma') }}"
         data-ruta-grados-url="{{ route('asistencia.opciones.grados') }}"
         data-csrf-token="{{ csrf_token() }}"
         data-ruta-toggle-activo="{{ route('participante.toggle-activo') }}"
         data-initial-search-lugar="{{ request('search_lugar', '') }}"
         data-initial-search-grado="{{ request('search_grado', '') }}"
    ></div>

    {{-- Div para pasar mensajes de sesión de Laravel a JavaScript --}}
    <div id="sessionMessages" class="hidden"
         @if (session('success')) data-success-message="{{ session('success') }}" @endif
         @if (session('error')) data-error-message="{{ session('error') }}" @endif
         @if (session('warning')) data-warning-message="{{ session('warning') }}" @endif
         @if (session('info')) data-info-message="{{ session('info') }}" @endif
    ></div>


    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .dark .overflow-x-auto::-webkit-scrollbar-track { background: #334155; } /* slate-700 */
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb { background: #475569; } /* slate-600 */
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #64748b; } /* slate-500 */
    </style>

    @vite(['resources/js/pages/participante-index.js'])

</x-app-layout>
