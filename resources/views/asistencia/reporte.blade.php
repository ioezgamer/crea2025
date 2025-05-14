<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Reporte de Asistencias</h2>
            <div class="flex items-center space-x-2">
                {{-- Verificar que la variable $filters y sus claves existan antes de usarlas --}}
                @if (isset($filters['programa']) && $filters['programa'])
                    <a href="{{ route('asistencia.exportPdf', [
                        'programa' => $filters['programa'],
                        'fecha_inicio' => $filters['fecha_inicio'],
                        'lugar_de_encuentro_del_programa' => $filters['lugar_de_encuentro_del_programa'] ?? '',
                        'grado_p' => $filters['grado_p'] ?? ''
                    ]) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H3a2 2 0 01-2-2V3a2 2 0 012-2h18a2 2 0 012 2v16a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar a PDF
                </a>
                @endif
            {{-- Botón Regresar --}}
            <x-boton-regresar onclick="window.location.href='{{ route('asistencia.create', [
                'programa' => $filters['programa'] ?? '',
                'fecha_inicio' => $filters['fecha_inicio'] ?? now()->startOfWeek()->format('Y-m-d'),
                'lugar_de_encuentro_del_programa' => $filters['lugar_de_encuentro_del_programa'] ?? '',
                'grado_p' => $filters['grado_p'] ?? ''
            ]) }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('asistencia.reporte') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="filtro_programa_reporte" class="block text-xs font-medium text-gray-700">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="filtro_programa_reporte" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione Programa...</option>
                            {{-- Usar $programOptions pasado desde el controlador --}}
                            @foreach ($programOptions ?? [] as $progOption)
                                <option value="{{ $progOption }}" {{ (isset($filters['programa']) && $filters['programa'] == $progOption) ? 'selected' : '' }}>
                                    {{ $progOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_lugar_reporte" class="block text-xs font-medium text-gray-700">Lugar</label>
                        <select name="lugar_de_encuentro_del_programa" id="filtro_lugar_reporte" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos los Lugares...</option>
                            {{-- Usar $lugarOptions pasado desde el controlador --}}
                            @foreach ($lugarOptions ?? [] as $lugarOption)
                                <option value="{{ $lugarOption }}" {{ (isset($filters['lugar_de_encuentro_del_programa']) && $filters['lugar_de_encuentro_del_programa'] == $lugarOption) ? 'selected' : '' }}>
                                    {{ $lugarOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('lugar_de_encuentro_del_programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_fecha_inicio_reporte" class="block text-xs font-medium text-gray-700">Semana (Lunes) <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="filtro_fecha_inicio_reporte" value="{{ $filters['fecha_inicio'] ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        @error('fecha_inicio') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="filtro_grado_reporte" class="block text-xs font-medium text-gray-700">Grado</label>
                        <select name="grado_p" id="filtro_grado_reporte" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos los Grados...</option>
                            {{-- Usar $gradoOptions pasado desde el controlador --}}
                            @foreach ($gradoOptions ?? [] as $gradoOption)
                                <option value="{{ $gradoOption }}" {{ (isset($filters['grado_p']) && $filters['grado_p'] == $gradoOption) ? 'selected' : '' }}>
                                    {{ $gradoOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('grado_p') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md text-xs">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md text-xs">
                    <p class="font-bold">Errores:</p>
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Asegurarse que $participantes esté definido y no sea nulo antes de llamar a isNotEmpty() --}}
            @if (isset($filters['programa']) && $filters['programa'] && isset($participantes) && $participantes->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen General del Reporte</h3>
                    <p class="text-sm text-gray-600">Programa: <span class="font-medium">{{ $filters['programa'] }}</span></p>
                    @if(isset($filters['lugar_de_encuentro_del_programa']) && $filters['lugar_de_encuentro_del_programa'])
                    <p class="text-sm text-gray-600">Lugar: <span class="font-medium">{{ $filters['lugar_de_encuentro_del_programa'] }}</span></p>
                    @endif
                    @if(isset($filters['grado_p']) && $filters['grado_p'])
                    <p class="text-sm text-gray-600">Grado: <span class="font-medium">{{ $filters['grado_p'] }}</span></p>
                    @endif
                    <p class="text-sm text-gray-600">Semana del: <span class="font-medium">{{ \Carbon\Carbon::parse($filters['fecha_inicio'])->format('d/m/Y') }}</span></p>
                    <p class="text-sm text-gray-600 mt-2">Total de participantes en este reporte: <span class="font-medium">{{ $totalParticipantes ?? 0 }}</span></p>
                    <p class="text-sm text-gray-600">Promedio general de asistencia: <span class="font-medium">{{ number_format($promedioAsistenciaGeneral ?? 0, 1) }}%</span></p>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas por Día</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-200">
                                <tr class="text-xs font-medium text-gray-600">
                                    <th class="px-4 py-3 text-left">Día</th>
                                    <th class="px-4 py-3 text-center">Presente</th>
                                    <th class="px-4 py-3 text-center">Ausente</th>
                                    <th class="px-4 py-3 text-center">Justificado</th>
                                    <th class="px-4 py-3 text-center">Total Registros</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($diasSemana ?? [] as $dia => $fecha)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $dia }} <span class="text-gray-500 text-xs">({{ \Carbon\Carbon::parse($fecha)->format('d/m') }})</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-green-600 font-medium">{{ $estadisticasPorDia[$dia]['Presente'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-red-600 font-medium">{{ $estadisticasPorDia[$dia]['Ausente'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-yellow-600 font-medium">{{ $estadisticasPorDia[$dia]['Justificado'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center text-gray-700 font-medium">{{ $estadisticasPorDia[$dia]['Total'] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles por Participante</h3>
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Nombres y apellidos</th>
                                    <th class="px-3 py-3 text-left">Género</th>
                                    <th class="px-3 py-3 text-left">Grado</th>
                                    <th class="px-3 py-3 text-left">Programa(s)</th>
                                    @foreach ($diasSemana ?? [] as $dia => $fecha)
                                        <th class="px-2 py-3 text-center whitespace-nowrap">
                                            @php
                                                $abreviaturas = ['Lunes'=>'Lun','Martes'=>'Mar','Miércoles'=>'Mié','Jueves'=>'Jue','Viernes'=>'Vie'];
                                            @endphp
                                            {{ $abreviaturas[$dia] ?? mb_substr($dia, 0, 3) }}
                                            <span class="block text-xxs">{{ \Carbon\Carbon::parse($fecha)->format('d') }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-3 py-3 text-center">Total P.</th>
                                    <th class="px-3 py-3 text-center">% Asist.</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($participantes as $participante)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                                        </td>
                                        <td class="px-3 py-3 text-gray-600">{{ $participante->genero }}</td>
                                        <td class="px-3 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                        <td class="px-3 py-3 text-gray-600 text-xs">{{ $participante->programa ?? 'N/A' }}</td>
                                        @foreach ($diasSemana as $dia => $fecha)
                                            <td class="px-2 py-3 text-center">
                                                @php
                                                    $estado = $asistencias[$participante->participante_id][$dia] ?? 'Ausente';
                                                    $colorClass = match ($estado) {
                                                        'Presente' => 'bg-green-100 text-green-700',
                                                        'Ausente' => 'bg-red-100 text-red-700',
                                                        'Justificado' => 'bg-yellow-100 text-yellow-700',
                                                        default => 'bg-gray-100 text-gray-700',
                                                    };
                                                @endphp
                                                <span class="px-1.5 py-0.5 rounded-full text-xxs font-semibold {{ $colorClass }}">
                                                    {{ substr($estado, 0, 1) }}
                                                </span>
                                            </td>
                                        @endforeach
                                        <td class="px-3 py-3 text-center font-medium">{{ $participante->totalAsistido ?? 0 }}</td>
                                        <td class="px-3 py-3 text-center font-medium">{{ number_format($participante->porcentajeAsistencia ?? 0, 0) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif (isset($filters['programa']) && $filters['programa'])
                <div class="bg-white shadow-sm rounded-lg p-6 text-sm text-gray-500">
                    No hay participantes que coincidan con los filtros seleccionados para generar el reporte.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6 text-sm text-gray-500">
                    Por favor, seleccione al menos un programa y una semana para generar el reporte.
                </div>
            @endif
        </div>
    </div>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
    </style>
</x-app-layout>
