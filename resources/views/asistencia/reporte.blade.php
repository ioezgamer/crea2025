<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Reporte de Asistencias</h2>
            <div class="flex items-center space-x-2">
                <!-- Botón para exportar a PDF -->
                @if (isset($programa) && $programa)
                    <a href="{{ route('asistencia.exportPdf', [
                        'programa' => $programa,
                        'fecha_inicio' => $fechaInicio,
                        'lugar_de_encuentro_del_programa' => $lugar_encuentro,
                        'grado_p' => $grado
                    ]) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H3a2 2 0 01-2-2V3a2 2 0 012-2h18a2 2 0 012 2v16a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar a PDF
                </a>
                @endif
            <h2 class="text-lg font-semibold text-gray-800">Reporte de Asistencias</h2>
            <x-boton-regresar onclick="window.location.href='{{ route('asistencia.create', [
                'programa' => $programa,
                'fecha_inicio' => $fechaInicio,
                'lugar_de_encuentro_del_programa' => $lugar_encuentro,
                'grado_p' => $grado
            ]) }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Formulario de filtros -->
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('asistencia.reporte') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="programa" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione...</option>
                            @foreach (['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'] as $prog)
                                <option value="{{ $prog }}" {{ $programa == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                            @endforeach
                        </select>
                        @error('programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Lugar</label>
                        <select name="lugar_de_encuentro_del_programa" id="lugar_de_encuentro_del_programa" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos...</option>
                            @foreach ($lugares_encuentro ?? [] as $lugar)
                                <option value="{{ $lugar }}" {{ $lugar_encuentro == $lugar ? 'selected' : '' }}>{{ $lugar }}</option>
                            @endforeach
                        </select>
                        @error('lugar_de_encuentro_del_programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Semana (Lunes) <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        @error('fecha_inicio') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Grado</label>
                        <select name="grado_p" id="grado_p" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione...</option>
                            @foreach ($grados ?? [] as $grado_item)
                                <option value="{{ $grado_item }}" {{ $grado == $grado_item ? 'selected' : '' }}>{{ $grado_item }}</option>
                            @endforeach
                        </select>
                        @error('grado_p') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </form>
            </div>

            <!-- Mensajes -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md">
                    <p class="font-bold">Errores:</p>
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Resumen general -->
            @if (isset($programa) && $programa && $participantes->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen General</h3>
                    <p class="text-sm text-gray-600">Total de participantes: {{ $totalParticipantes }}</p>
                    <p class="text-sm text-gray-600">Promedio de asistencia: {{ number_format($promedioAsistencia, 1) }}%</p>
                </div>

                <!-- Estadísticas por día -->
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
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($diasSemana as $dia => $fecha)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $dia }} {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-green-600">{{ $estadisticasPorDia[$dia]['Presente'] }}</td>
                                        <td class="px-4 py-3 text-center text-red-600">{{ $estadisticasPorDia[$dia]['Ausente'] }}</td>
                                        <td class="px-4 py-3 text-center text-yellow-600">{{ $estadisticasPorDia[$dia]['Justificado'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabla de asistencias -->
                <div class="bg-transparent p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles por Participante</h3>
                    <div class="overflow-x-auto rounded-2xl border-x-2 border-y-2 shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-200">
                                <tr class="text-xs font-medium text-gray-600">
                                    <th class="px-4 py-3 text-left">Nombres y apellidos</th>
                                    <th class="px-4 py-3 text-left">Género</th>
                                    <th class="px-4 py-3 text-left">Grado</th>
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    @foreach ($diasSemana as $dia => $fecha)
                                        <th class="px-4 py-3 text-center">
                                            @php
                                                $abreviaturas = [
                                                    'Lunes' => 'Lun',
                                                    'Martes' => 'Mar',
                                                    'Miércoles' => 'Mié',
                                                    'Jueves' => 'Jue',
                                                    'Viernes' => 'Vie',
                                                    'Sábado' => 'Sáb',
                                                    'Domingo' => 'Dom',
                                                ];
                                                $diaAbreviado = $abreviaturas[$dia] ?? mb_substr($dia, 0, 3, 'UTF-8');
                                            @endphp
                                            {{ $diaAbreviado }} {{ \Carbon\Carbon::parse($fecha)->format('d') }}
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center">Total</th>
                                    <th class="px-4 py-3 text-center">%</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 rounded-2xl">
                                @foreach ($participantes as $participante)
                                    <tr class="hover:bg-sky-100">
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->genero }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->programa ?? 'N/A' }}</td>
                                        @foreach ($diasSemana as $dia => $fecha)
                                            <td class="px-4 py-3 text-center">
                                                @php
                                                    $estado = $asistencias[$participante->participante_id][$dia];
                                                    $color = match ($estado) {
                                                        'Presente' => 'text-green-600',
                                                        'Ausente' => 'text-red-600',
                                                        'Justificado' => 'text-yellow-600',
                                                        default => 'text-gray-600',
                                                    };
                                                @endphp
                                                <span class="{{ $color }}">{{ substr($estado, 0, 1) }}</span>
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3 text-center">{{ $participante->totalAsistido ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif (isset($programa) && $programa)
                <div class="bg-white shadow-sm rounded-lg p-6 text-sm text-gray-500">No hay participantes inscritos.</div>
            @endif
        </div>
    </div>
</x-app-layout>