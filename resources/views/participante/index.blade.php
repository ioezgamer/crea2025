<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    Gestión de Participantes
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-600">
                    @if(request('grado'))
                        Participantes en el grado: <span class="font-semibold">{{ urldecode(request('grado')) }}</span>
                    @elseif(request('search_name') || request('search_programa') || request('search_lugar'))
                        Resultados de la búsqueda
                    @else
                        Listado completo de participantes
                    @endif
                    <span class="text-slate-500 text-xs"> (Total: {{ $participantes->total() }})</span>
                </p>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-3">
                {{-- Import Button --}}
                <a href="{{ route('participantes.import.form') }}" title="Importar Participantes"
                   class="group relative inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-sky-500 to-cyan-600 border-2 sm:border-4 border-white shadow-lg rounded-full text-white text-xs tracking-widest transition-all duration-300 ease-in-out overflow-hidden hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-white transition-transform duration-300 ease-in-out group-hover:-translate-y-10" viewBox="0 0 384 512">
                        <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path>
                    </svg>
                    <span class="absolute text-white whitespace-nowrap text-xs sm:text-sm font-medium opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 ease-in-out">
                        Importar
                    </span>
                </a>
                
                {{-- Export Button --}}
                <a href="{{ route('participantes.export', request()->query()) }}" title="Exportar Participantes Filtrados"
                   class="group relative inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-emerald-600 border-2 sm:border-4 border-white shadow-lg rounded-full text-white text-xs tracking-widest transition-all duration-300 ease-in-out overflow-hidden hover:w-36 hover:sm:w-40 hover:rounded-full active:scale-90 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white transition-transform duration-300 ease-in-out group-hover:-translate-y-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="absolute text-white whitespace-nowrap text-xs sm:text-sm font-medium opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 ease-in-out">
                        Exportar
                    </span>
                </a>

                {{-- Create Participant Button (assuming x-crear-button is styled consistently) --}}
                {{-- If x-crear-button needs specific styling for this context, adjust its internal classes or pass them here --}}
                <x-crear-button onclick="window.location.href='{{ route('participante.create') }}'">
                    {{-- Content for x-crear-button, if it uses a slot, e.g., "Nuevo" or an icon --}}
                </x-crear-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl overflow-hidden">
                {{-- Filter Form Section --}}
                <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                    <form method="GET" action="{{ request('grado') ? route('participante.indexByGrade', ['grado' => request('grado')]) : route('participante.index') }}" id="filterForm" class="space-y-4 md:space-y-0 md:grid md:grid-cols-12 md:gap-4 md:items-end">
                        
                        @if(request('grado') && !request()->routeIs('participante.index'))
                            <input type="hidden" name="grado" value="{{ request('grado') }}">
                        @endif

                        <div class="col-span-12 md:col-span-4 lg:col-span-3">
                            <label for="search_name" class="block text-xs font-medium text-slate-700">Buscar por Nombre/Apellido</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                </div>
                                <input type="text" name="search_name" id="search_name" placeholder="Nombre o apellido..." value="{{ request('search_name') }}"
                                       class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-4 lg:col-span-3">
                            <label for="search_programa" class="block text-xs font-medium text-slate-700">Programa</label>
                            <select name="search_programa" id="search_programa"
                                    class="mt-1 block w-full pl-3 pr-8 py-2.5 border border-slate-300 bg-white rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                                <option value="">Todos los programas</option>
                                @foreach($programas as $programaOption)
                                    <option value="{{ $programaOption }}" {{ request('search_programa') == $programaOption ? 'selected' : '' }}>
                                        {{ $programaOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-4 lg:col-span-3">
                            <label for="search_lugar" class="block text-xs font-medium text-slate-700">Lugar de Encuentro</label>
                            <select name="search_lugar" id="search_lugar"
                                    class="mt-1 block w-full pl-3 pr-8 py-2.5 border border-slate-300 bg-white rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                                    {{ request('search_programa') ? '' : 'disabled' }}>
                                <option value="">{{ request('search_programa') ? 'Todos los lugares' : 'Seleccione programa primero' }}</option>
                            </select>
                        </div>
                        
                        <div class="col-span-12 md:col-span-12 lg:col-span-3 flex items-end space-x-2">
                            <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Filtrar
                            </button>
                            <a href="{{ request('grado') ? route('participante.indexByGrade', ['grado' => request('grado')]) : route('participante.index') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-slate-200 border border-transparent rounded-xl font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md" title="Limpiar filtros">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
                
                <div id="global_feedback_toast" class="fixed top-20 right-5 z-[100]"></div> {{-- Ensure high z-index for toast --}}

                <div class="p-2 sm:p-4">
                    @if($participantes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-3 text-lg font-semibold text-slate-800">No se encontraron participantes</h3>
                            <p class="mt-1 text-sm text-slate-500">Intenta ajustar tus criterios de búsqueda o crea un nuevo participante.</p>
                            <div class="mt-6">
                                <x-crear-button onclick="window.location.href='{{ route('participante.create') }}'">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Nuevo Participante
                                </x-crear-button>
                            </div>
                        </div>
                    @else
                        <div class="mb-4 px-2 text-xs text-slate-600">
                            Mostrando <span class="font-semibold">{{ $participantes->firstItem() }}</span> a <span class="font-semibold">{{ $participantes->lastItem() }}</span> de <span class="font-semibold">{{ $participantes->total() }}</span> resultados.
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Participante</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Días Asist.</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Grado</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Edad</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Programa(s)</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Lugar</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tutor</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Activo</th>
                                        @can('manage-roles')
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach ($participantes as $participante)
                                        <tr class="hover:bg-sky-50/70 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-slate-700">
                                                #{{ $participante->participante_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <a href="{{ route('participante.show', $participante) }}" class="hover:text-indigo-700 hover:underline">
                                                        {{ $participante->primer_nombre_p ?? 'N/A' }} {{ $participante->segundo_nombre_p ?? '' }} 
                                                        {{ $participante->primer_apellido_p ?? 'N/A' }} {{ $participante->segundo_apellido_p ?? '' }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $participante->comunidad_p ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-slate-500">
                                                @if($participante->dias_de_asistencia_al_programa)
                                                    @php $dias = explode(',', $participante->dias_de_asistencia_al_programa); @endphp
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($dias as $dia)
                                                            <span class="px-1.5 py-0.5 bg-slate-200 text-slate-700 rounded-full text-xxs font-medium">{{ trim($dia) }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-slate-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-600">{{ $participante->edad_p ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-600">
                                                @if($participante->programa)
                                                    @php $programasPrincipales = explode(',', $participante->programa); @endphp
                                                    @foreach($programasPrincipales as $prog)
                                                        <span class="block mb-0.5 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xxs font-semibold">{{ trim($prog) }}</span>
                                                    @endforeach
                                                @endif
                                                @if($participante->programas) {{-- Assuming 'programas' is for sub-programs --}}
                                                    @php $subProgramas = explode(',', $participante->programas); @endphp
                                                    @foreach($subProgramas as $subProg)
                                                        <span class="block mt-0.5 px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xxs font-semibold">{{ trim($subProg) }}</span>
                                                    @endforeach
                                                @endif
                                                @if(!$participante->programa && !$participante->programas)
                                                    <span class="text-slate-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-600">{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @can('manage-roles')
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer toggle-activo" 
                                                               data-participante-id="{{ $participante->participante_id }}"
                                                               {{ $participante->activo ? 'checked' : '' }}>
                                                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                                    </label>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participante->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $participante->activo ? 'Sí' : 'No' }}
                                                    </span>
                                                @endcan
                                            </td>
                                            @can('manage-roles')
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-1">
                                                    <a href="{{ route('participante.edit', $participante) }}" class="text-amber-500 hover:text-amber-700 p-1.5 rounded-full hover:bg-amber-100 transition-colors" title="Editar">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <form action="{{ route('participante.destroy', $participante) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este participante? Esta acción no se puede deshacer.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-full hover:bg-red-100 transition-colors" title="Eliminar">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                        
                        <div class="px-4 py-3 bg-white border-t border-slate-200 rounded-b-xl">
                            {{ $participantes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchProgramaSelect = document.getElementById('search_programa');
        const searchLugarSelect = document.getElementById('search_lugar');
        const initialLugarValue = "{{ request('search_lugar', '') }}";
        const RUTA_LUGARES_POR_PROGRAMA = "{{ route('participante.lugaresPorPrograma') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}"; // Ensure this is available if your fetch needs it for POST/PUT/DELETE

        function populateLugarSelect(lugares, selectedValue = "") {
            searchLugarSelect.innerHTML = '<option value="">Todos los lugares</option>'; 
            if (lugares && lugares.length > 0) {
                lugares.forEach(lugar => {
                    const option = document.createElement('option');
                    option.value = lugar;
                    option.textContent = lugar;
                    if (lugar === selectedValue) {
                        option.selected = true;
                    }
                    searchLugarSelect.appendChild(option);
                });
                searchLugarSelect.disabled = false;
            } else {
                searchLugarSelect.disabled = true;
                if (searchProgramaSelect.value) { 
                    searchLugarSelect.innerHTML = '<option value="">No hay lugares para este programa</option>';
                } else {
                    searchLugarSelect.innerHTML = '<option value="">Seleccione programa primero</option>';
                }
            }
        }

        if (searchProgramaSelect) {
            searchProgramaSelect.addEventListener('change', function () {
                const programa = this.value;
                searchLugarSelect.innerHTML = '<option value="">Cargando lugares...</option>';
                searchLugarSelect.disabled = true;

                if (programa) {
                    fetch(`${RUTA_LUGARES_POR_PROGRAMA}?programa=${encodeURIComponent(programa)}`, {
                        method: 'GET',
                        headers: {
                            // 'X-CSRF-TOKEN': CSRF_TOKEN, // Not needed for GET typically
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Respuesta no OK:', { status: response.status, text });
                                throw new Error(`Error HTTP ${response.status}: ${text.substring(0, 200)}...`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateLugarSelect(data.lugares || data); // Adapt based on actual JSON structure
                    })
                    .catch(error => {
                        console.error('Error en fetch para cargar lugares:', error);
                        searchLugarSelect.innerHTML = '<option value="">Error al cargar lugares</option>';
                        searchLugarSelect.disabled = true;
                        showToast(`Error al cargar lugares: ${error.message}`, 'error');
                    });
                } else {
                    populateLugarSelect([]); 
                }
            });

            // Initial load for 'Lugar de Encuentro' if a program is already selected
            if (searchProgramaSelect.value) {
                fetch(`${RUTA_LUGARES_POR_PROGRAMA}?programa=${encodeURIComponent(searchProgramaSelect.value)}`, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.ok ? response.json() : Promise.reject(response))
                .then(data => {
                    populateLugarSelect(data.lugares || data, initialLugarValue);
                })
                .catch(async error => {
                    let errorMsg = 'Error al cargar lugares iniciales.';
                    try {
                        const errText = await error.text();
                        errorMsg = `Error HTTP ${error.status}: ${errText.substring(0,100)}`;
                    } catch (e) { /* ignore */ }
                    console.error('Error en fetch para cargar lugares iniciales:', errorMsg);
                    if (!initialLugarValue && searchProgramaSelect.value) { 
                        searchLugarSelect.innerHTML = '<option value="">Error al cargar</option>';
                        searchLugarSelect.disabled = true;
                    }
                     // showToast is defined below
                });
            } else {
                searchLugarSelect.disabled = true;
                searchLugarSelect.innerHTML = '<option value="">Seleccione programa primero</option>';
            }
        }

        const toggles = document.querySelectorAll('.toggle-activo');
        const feedbackToastContainer = document.getElementById('global_feedback_toast');

        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            // Tailwind classes for the toast, adjusted for light theme
            let bgColor, textColor, borderColor, iconSvg;

            if (type === 'success') {
                bgColor = 'bg-green-50'; textColor = 'text-green-700'; borderColor = 'border-green-400';
                iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
            } else { // error
                bgColor = 'bg-red-50'; textColor = 'text-red-700'; borderColor = 'border-red-400';
                iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 102 0V5zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>`;
            }
            toast.className = `flex items-center p-4 rounded-xl shadow-lg border-l-4 ${bgColor} ${textColor} ${borderColor} text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0`;
            
            toast.innerHTML = `${iconSvg}<span>${message}</span>`;
            
            feedbackToastContainer.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            });

            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, 4000);
        }

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const participanteId = parseInt(this.getAttribute('data-participante-id'), 10);
                const activo = this.checked;
                const originalState = !activo; // State before change
                this.disabled = true; // Disable toggle during request

                fetch('{{ route('participante.toggle-activo') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ participante_id: participanteId, activo: activo })
                })
                .then(response => {
                    if (!response.ok) {
                        // Attempt to parse JSON error first
                        return response.json().then(errData => {
                            throw { status: response.status, data: errData, response: response };
                        }).catch(() => { // If not JSON, parse as text
                            return response.text().then(text => {
                               throw { status: response.status, message: `Respuesta no JSON: ${text.substring(0,100)}...`, response: response };
                            });
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Estado actualizado con éxito.', 'success');
                    } else {
                        showToast(data.message || 'Error al actualizar el estado.', 'error');
                        this.checked = originalState; // Revert toggle on error
                    }
                })
                .catch(error => {
                    console.error('Error en toggleActivo:', error);
                    let errorMessage = 'Error de conexión o respuesta inesperada del servidor.';
                    if (error.message) { // For network errors or text responses
                        errorMessage = error.message;
                    } else if (error.data && error.data.message) { // For JSON errors from server
                        errorMessage = error.data.message;
                    }
                    showToast(errorMessage, 'error');
                    this.checked = originalState; // Revert toggle on critical error
                })
                .finally(() => {
                    this.disabled = false; // Re-enable toggle
                });
            });
        });
    });
    </script>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; } /* For very small text like day tags */
        /* Custom scrollbar for Webkit browsers (Chrome, Safari, Edge) */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9; /* slate-100 */
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1; /* slate-300 */
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; /* slate-500 */
        }
    </style>
</x-app-layout>
