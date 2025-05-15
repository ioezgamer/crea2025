<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0">
            <div>
                <h2 class="text-2xl text-left lg:text-3xl font-bold text-gray-800">Gestión de participantes</h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 text-left">
                    @if(request('grado'))
                        Participantes en el grado: <span class="font-semibold">{{ urldecode(request('grado')) }}</span>
                    @elseif(request('search_name') || request('search_programa') || request('search_lugar'))
                        Resultados de la búsqueda
                    @else
                        Listado completo de participantes
                    @endif
                    <span class="text-gray-400 text-xs"> (Total: {{ $participantes->total() }})</span>
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('participantes.import.form') }}" title="Importar Participantes"
                   class="group inline-flex items-center px-2 py-2 bg-indigo-600 border border-transparent rounded-full  text-xs text-white tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-8 h-8 group-hover:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span class="hidden group-hover:inline">Importar</span>
                </a>
                <a href="{{ route('participantes.export', request()->query()) }}" title="Exportar Participantes Filtrados"
                   class="group inline-flex items-center px-2 py-2 bg-green-600 border border-transparent rounded-full  text-xs text-white tracking-widest  hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-8 h-8 group-hover:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span class="hidden group-hover:inline">Exportar</span>
                </a>
                <x-crear-button onclick="window.location.href='{{ route('participante.create') }}'">
                </x-crear-button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-full px-2 sm:px-4 lg:px-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-4 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ request('grado') ? route('participante.indexByGrade', ['grado' => request('grado')]) : route('participante.index') }}" id="filterForm" class="space-y-3 md:space-y-0 md:grid md:grid-cols-12 md:gap-3 md:items-end">
                        
                        @if(request('grado') && !request()->routeIs('participante.index'))
                            <input type="hidden" name="grado" value="{{ request('grado') }}">
                        @endif

                        <div class="col-span-12 md:col-span-3">
                            <label for="search_name" class="block text-xs font-medium text-gray-700">Buscar por Nombre/Apellido</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                </div>
                                <input type="text" name="search_name" id="search_name" placeholder="Nombre o apellido..." value="{{ request('search_name') }}"
                                       class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label for="search_programa" class="block text-xs font-medium text-gray-700">Programa</label>
                            <select name="search_programa" id="search_programa"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los programas</option>
                                @foreach($programas as $programaOption)
                                    <option value="{{ $programaOption }}" {{ request('search_programa') == $programaOption ? 'selected' : '' }}>
                                        {{ $programaOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label for="search_lugar" class="block text-xs font-medium text-gray-700">Lugar de Encuentro</label>
                            <select name="search_lugar" id="search_lugar"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    {{ request('search_programa') ? '' : 'disabled' }}>
                                <option value="">{{ request('search_programa') ? 'Todos los lugares' : 'Seleccione programa primero' }}</option>
                            </select>
                        </div>
                        
                        <div class="col-span-12 md:col-span-3 flex items-end space-x-2">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Filtrar
                            </button>
                            <a href="{{ request('grado') ? route('participante.indexByGrade', ['grado' => request('grado')]) : route('participante.index') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150" title="Limpiar filtros">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
                
                <div id="global_feedback_toast" class="fixed top-20 right-5 z-50"></div>

                <div class="p-2 sm:p-4">
                    @if($participantes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No se encontraron participantes</h3>
                            <p class="mt-1 text-sm text-gray-500">Intenta ajustar tus criterios de búsqueda o crea un nuevo participante.</p>
                            <div class="mt-6">
                                <x-crear-button onclick="window.location.href='{{ route('participante.create') }}'">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Nuevo Participante
                                </x-crear-button>
                            </div>
                        </div>
                    @else
                        <div class="mb-4 px-2 text-xs text-gray-600">
                            Mostrando <span class="font-semibold">{{ $participantes->firstItem() }}</span> a <span class="font-semibold">{{ $participantes->lastItem() }}</span> de <span class="font-semibold">{{ $participantes->total() }}</span> resultados.
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Participante</th>
                                        <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Días Asist.</th>
                                        <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Grado</th>
                                        <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Edad</th>
                                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Programa(s)</th>
                                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lugar</th>
                                        <th scope="col" class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tutor</th>
                                        <th scope="col" class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Activo</th>
                                        @can('manage-roles')
                                        <th scope="col" class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($participantes as $participante)
                                        <tr class="hover:bg-sky-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-700">
                                                #{{ $participante->participante_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('participante.show', $participante) }}" class="hover:text-blue-700 hover:underline">
                                                        {{ $participante->primer_nombre_p ?? 'N/A' }} {{ $participante->segundo_nombre_p ?? '' }} 
                                                        {{ $participante->primer_apellido_p ?? 'N/A' }} {{ $participante->segundo_apellido_p ?? '' }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $participante->comunidad_p ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-500">
                                                @if($participante->dias_de_asistencia_al_programa)
                                                    @php $dias = explode(',', $participante->dias_de_asistencia_al_programa); @endphp
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($dias as $dia)
                                                            <span class="px-1.5 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xxs font-medium">{{ trim($dia) }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-600">{{ $participante->edad_p ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                @if($participante->programa)
                                                    @php $programasPrincipales = explode(',', $participante->programa); @endphp
                                                    @foreach($programasPrincipales as $prog)
                                                        <span class="block mb-0.5 px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xxs font-semibold">{{ trim($prog) }}</span>
                                                    @endforeach
                                                @endif
                                                @if($participante->programas)
                                                    @php $subProgramas = explode(',', $participante->programas); @endphp
                                                    @foreach($subProgramas as $subProg)
                                                        <span class="block mt-0.5 px-1.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xxs font-semibold">{{ trim($subProg) }}</span>
                                                    @endforeach
                                                @endif
                                                @if(!$participante->programa && !$participante->programas)
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @can('manage-roles')
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer toggle-activo" 
                                                               data-participante-id="{{ $participante->participante_id }}"
                                                               {{ $participante->activo ? 'checked' : '' }}>
                                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                                    </label>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participante->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $participante->activo ? 'Sí' : 'No' }}
                                                    </span>
                                                @endcan
                                            </td>
                                            @can('manage-roles')
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-1.5">
                                                    <a href="{{ route('participante.edit', $participante) }}" class="text-yellow-500 hover:text-yellow-700 p-1.5 rounded-full hover:bg-yellow-100 transition-colors" title="Editar">
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
                        
                        <div class="px-4 py-3 bg-white border-t border-gray-200">
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
        const CSRF_TOKEN = "{{ csrf_token() }}";

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
                            'X-CSRF-TOKEN': CSRF_TOKEN,
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
                        console.log('Lugares recibidos:', data);
                        populateLugarSelect(data);
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

            if (searchProgramaSelect.value) {
                fetch(`${RUTA_LUGARES_POR_PROGRAMA}?programa=${encodeURIComponent(searchProgramaSelect.value)}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Respuesta no OK (inicial):', { status: response.status, text });
                            throw new Error(`Error HTTP ${response.status}: ${text.substring(0, 200)}...`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Lugares iniciales recibidos:', data);
                    populateLugarSelect(data, initialLugarValue);
                })
                .catch(error => {
                    console.error('Error en fetch para cargar lugares iniciales:', error);
                    if (!initialLugarValue && searchProgramaSelect.value) { 
                        searchLugarSelect.innerHTML = '<option value="">Error al cargar lugares</option>';
                        searchLugarSelect.disabled = true;
                    }
                    showToast(`Error al cargar lugares iniciales: ${error.message}`, 'error');
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
            toast.className = `p-3 rounded-md shadow-lg text-xs font-medium mb-2 transition-all duration-300 ease-in-out transform translate-x-full`;
            
            let bgColor, textColor, borderColor, iconSvg;

            if (type === 'success') {
                bgColor = 'bg-green-50'; textColor = 'text-green-700'; borderColor = 'border-green-300';
                iconSvg = `<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
            } else { 
                bgColor = 'bg-red-50'; textColor = 'text-red-700'; borderColor = 'border-red-300';
                iconSvg = `<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 102 0V5zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>`;
            }
            toast.classList.add(bgColor, textColor, borderColor, 'border-l-4');
            toast.innerHTML = `<div class="flex items-center">${iconSvg}<span>${message}</span></div>`;
            
            feedbackToastContainer.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => { toast.remove(); }, 300);
            }, 4000);
        }

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const participanteId = parseInt(this.getAttribute('data-participante-id'), 10);
                const activo = this.checked;
                const originalState = !activo;
                this.disabled = true;

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
                        return response.json().then(errData => {
                            throw { status: response.status, data: errData, response: response };
                        }).catch(() => {
                            return response.text().then(text => {
                               throw { status: response.status, message: `Respuesta no JSON: ${text.substring(0,100)}...`, response: response };
                            });
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Estado actualizado.', 'success');
                    } else {
                        showToast(data.message || 'Error al actualizar.', 'error');
                        this.checked = originalState;
                    }
                })
                .catch(error => {
                    console.error('Error en toggleActivo:', error);
                    let errorMessage = 'Error de conexión o respuesta inesperada.';
                    if (error.message) {
                        errorMessage = error.message;
                    } else if (error.data && error.data.message) {
                        errorMessage = error.data.message;
                    }
                    showToast(errorMessage, 'error');
                    this.checked = originalState;
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    });
    </script>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
    </style>
</x-app-layout>