<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 leading-tight">
                Reporte de Asistencias
            </h2>
            <div class="flex items-center space-x-2">
                {{-- Botón Exportar a PDF --}}
                @if (isset($filters['programa']) && $filters['programa'] && isset($participantes) && $participantes->isNotEmpty())
                    <a href="{{ route('asistencia.exportPdf', array_merge($filters, ['lugar_de_encuentro_del_programa' => $filters['lugar_de_encuentro_del_programa'] ?? '', 'grado_p' => $filters['grado_p'] ?? ''])) }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H3a2 2 0 01-2-2V3a2 2 0 012-2h18a2 2 0 012 2v16a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </a>
                @endif

                {{-- Botón Regresar --}}
                <x-boton-regresar 
                    aria-label="Volver a la página de registro de asistencia"
                    onclick="window.location.href='{{ route('asistencia.create', [
                        'programa' => $filters['programa'] ?? '',
                        'fecha_inicio' => $filters['fecha_inicio'] ?? now()->startOfWeek()->format('Y-m-d'),
                        'lugar_de_encuentro_del_programa' => $filters['lugar_de_encuentro_del_programa'] ?? '',
                        'grado_p' => $filters['grado_p'] ?? ''
                    ]) }}'" 
                />
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Sección de Filtros --}}
            <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 mb-6 border border-gray-200">
                <form method="GET" action="{{ route('asistencia.reporte') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="filtro_programa_reporte" class="block text-xs font-medium text-gray-700 mb-1">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="filtro_programa_reporte" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione Programa...</option>
                            @foreach ($programOptions ?? [] as $progOption)
                                <option value="{{ $progOption }}" {{ (isset($filters['programa']) && $filters['programa'] == $progOption) ? 'selected' : '' }}>
                                    {{ $progOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('programa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_lugar_reporte" class="block text-xs font-medium text-gray-700 mb-1">Lugar</label>
                        <select name="lugar_de_encuentro_del_programa" id="filtro_lugar_reporte" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos los Lugares...</option>
                            @foreach ($lugarOptions ?? [] as $lugarOption)
                                <option value="{{ $lugarOption }}" {{ (isset($filters['lugar_de_encuentro_del_programa']) && $filters['lugar_de_encuentro_del_programa'] == $lugarOption) ? 'selected' : '' }}>
                                    {{ $lugarOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('lugar_de_encuentro_del_programa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_fecha_inicio_reporte" class="block text-xs font-medium text-gray-700 mb-1">Semana (Lunes) <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="filtro_fecha_inicio_reporte" value="{{ $filters['fecha_inicio'] ?? now()->startOfWeek()->format('Y-m-d') }}" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        @error('fecha_inicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_grado_reporte" class="block text-xs font-medium text-gray-700 mb-1">Grado</label>
                        <select name="grado_p" id="filtro_grado_reporte" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos los Grados...</option>
                            @foreach ($gradoOptions ?? [] as $gradoOption)
                                <option value="{{ $gradoOption }}" {{ (isset($filters['grado_p']) && $filters['grado_p'] == $gradoOption) ? 'selected' : '' }}>
                                    {{ $gradoOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('grado_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </form>
            </div>

            {{-- Mensajes de Feedback --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md text-sm shadow-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md text-sm shadow-md" role="alert">
                    <p class="font-bold">Por favor corrige los siguientes errores:</p>
                    <ul class="mt-2 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Contenido del Reporte --}}
            @if (isset($filters['programa']) && $filters['programa'] && isset($participantes) && $participantes->isNotEmpty())
                <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Resumen General del Reporte</h3>
                    <hr class="mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-2 text-sm">
                        <p class="text-gray-600">Programa: <span class="font-medium text-gray-900">{{ $filters['programa'] }}</span></p>
                        @if(isset($filters['lugar_de_encuentro_del_programa']) && $filters['lugar_de_encuentro_del_programa'])
                        <p class="text-gray-600">Lugar: <span class="font-medium text-gray-900">{{ $filters['lugar_de_encuentro_del_programa'] }}</span></p>
                        @endif
                        @if(isset($filters['grado_p']) && $filters['grado_p'])
                        <p class="text-gray-600">Grado: <span class="font-medium text-gray-900">{{ $filters['grado_p'] }}</span></p>
                        @endif
                        <p class="text-gray-600">Semana del: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($filters['fecha_inicio'])->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span></p>
                        <p class="text-gray-600">Total de participantes: <span class="font-medium text-gray-900">{{ $totalParticipantes ?? 0 }}</span></p>
                        <p class="text-gray-600">Promedio de asistencia: <span class="font-medium text-gray-900">{{ number_format($promedioAsistenciaGeneral ?? 0, 1) }}%</span></p>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas por Día</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Día</th>
                                    <th class="px-4 py-3 text-center">Presente</th>
                                    <th class="px-4 py-3 text-center">Ausente</th>
                                    <th class="px-4 py-3 text-center">Justificado</th>
                                    <th class="px-4 py-3 text-center">Total Registros</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($diasSemana ?? [] as $dia => $fecha)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-medium">
                                            {{ $dia }} <span class="text-gray-500 text-xs">({{ \Carbon\Carbon::parse($fecha)->format('d/m') }})</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $estadisticasPorDia[$dia]['Presente'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-red-600 font-semibold">{{ $estadisticasPorDia[$dia]['Ausente'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-yellow-600 font-semibold">{{ $estadisticasPorDia[$dia]['Justificado'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700 font-semibold">{{ $estadisticasPorDia[$dia]['Total'] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles por Participante</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left sticky left-0 bg-gray-100 z-10">Nombres y apellidos</th>
                                    <th class="px-3 py-3 text-left">Género</th>
                                    <th class="px-3 py-3 text-left">Grado</th>
                                    <th class="px-3 py-3 text-left">Programa(s)</th>
                                    @foreach ($diasSemana ?? [] as $dia => $fecha)
                                        <th class="px-2 py-3 text-center whitespace-nowrap">
                                            @php
                                                $abreviaturas = ['Lunes'=>'Lun','Martes'=>'Mar','Miércoles'=>'Mié','Jueves'=>'Jue','Viernes'=>'Vie','Sábado'=>'Sáb','Domingo'=>'Dom'];
                                            @endphp
                                            {{ $abreviaturas[$dia] ?? mb_substr($dia, 0, 3) }}
                                            <span class="block text-xxs text-gray-400">{{ \Carbon\Carbon::parse($fecha)->format('d') }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-3 py-3 text-center">Total P.</th>
                                    <th class="px-3 py-3 text-center">% Asist.</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($participantes as $participante)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-900 sticky left-0 bg-white hover:bg-gray-50 z-10">
                                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                                        </td>
                                        <td class="px-3 py-3 text-gray-600">{{ $participante->genero }}</td>
                                        <td class="px-3 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                        <td class="px-3 py-3 text-gray-600 text-xs">{{ $participante->programa ?? 'N/A' }}</td>
                                        @foreach ($diasSemana as $dia => $fecha)
                                            <td class="px-2 py-3 text-center">
                                                @php
                                                    $estado = $asistencias[$participante->participante_id][$dia] ?? 'N/A'; // N/A si no aplica o no hay registro
                                                    $colorClass = match ($estado) {
                                                        'Presente' => 'bg-green-100 text-green-700',
                                                        'Ausente' => 'bg-red-100 text-red-700',
                                                        'Justificado' => 'bg-yellow-100 text-yellow-700',
                                                        default => 'bg-gray-100 text-gray-500', // Para N/A o si el día no aplica
                                                    };
                                                    $letraEstado = match ($estado) {
                                                        'Presente' => 'P',
                                                        'Ausente' => 'A',
                                                        'Justificado' => 'J',
                                                        default => '-', // Para N/A o si el día no aplica
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 rounded-full text-xxs font-semibold {{ $colorClass }}">
                                                    {{ $letraEstado }}
                                                </span>
                                            </td>
                                        @endforeach
                                        <td class="px-3 py-3 text-center font-semibold text-gray-700">{{ $participante->totalAsistido ?? 0 }}</td>
                                        <td class="px-3 py-3 text-center font-semibold text-gray-700">{{ number_format($participante->porcentajeAsistencia ?? 0, 0) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif (isset($filters['programa']) && $filters['programa'])
                <div class="bg-white shadow-md rounded-xl p-6 text-sm text-gray-600 text-center border border-gray-200">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Sin Resultados</h3>
                    <p class="mt-1 text-sm text-gray-500">No se encontraron participantes que coincidan con los filtros seleccionados para generar el reporte.</p>
                </div>
            @else
                <div class="bg-white shadow-md rounded-xl p-6 text-sm text-gray-600 text-center border border-gray-200">
                     <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Seleccione Filtros</h3>
                    <p class="mt-1 text-sm text-gray-500">Por favor, seleccione al menos un programa y una semana para generar el reporte.</p>
                </div>
            @endif
        </div>
    </div>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
        /* Para hacer la primera columna de la tabla de detalles fija */
        .sticky.left-0 {
            position: -webkit-sticky; /* Para Safari */
            position: sticky;
            left: 0;
            z-index: 10; /* Asegura que esté sobre otras celdas pero debajo del header si se hace sticky también */
        }
        /* Opcional: añadir un pequeño borde a la derecha de la columna fija para separarla visualmente al hacer scroll */
        /*
        .sticky.left-0:after {
            content: '';
            position: absolute;
            top: 0;
            right: -1px; // Posición del borde
            bottom: 0;
            width: 1px;
            background: #e5e7eb; // color del borde (gray-200)
            z-index: 15;
        }
        */
    </style>
</x-app-layout>
