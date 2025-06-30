<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencias</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #e2e8f0; font-weight: bold; }
        .text-center { text-align: center; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-yellow { color: #d97706; }
        .summary { margin-bottom: 20px; }
        .summary p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Reporte de Asistencias</h1>
    {{-- Se accede a las variables desde el arreglo $filters --}}
    <p><strong>Programa:</strong> {{ $filters['programa'] }}</p>
    <p><strong>Fecha de inicio:</strong> {{ \Carbon\Carbon::parse($filters['fecha'])->format('d/m/Y') }}</p>
    <p><strong>Lugar:</strong> {{ $filters['lugar_de_encuentro_del_programa'] ?? 'Todos' }}</p>
    <p><strong>Grado:</strong> {{ $filters['grado_p'] ?? 'Todos' }}</p>

    @if ($participantes->isNotEmpty())
        <!-- Resumen general -->
        <div class="summary">
            <h2>Resumen General</h2>
            <p>Total de participantes: {{ $totalParticipantes }}</p>
            {{-- Se usa el nombre de variable correcto pasado desde el controlador --}}
            <p>Promedio de asistencia: {{ number_format($promedioAsistenciaGeneral, 1) }}%</p>
        </div>

        <!-- Estadísticas por día -->
        <h2>Estadísticas por Día</h2>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th class="text-center">Presente</th>
                    <th class="text-center">Ausente</th>
                    <th class="text-center">Justificado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($diasSemana as $dia => $fecha)
                    @php
                        // Normalizar el nombre del día para evitar problemas con acentos o mayúsculas
                        $diaNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], mb_strtolower($dia));
                        $diasValidos = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
                    @endphp
                    @if (in_array($diaNormalizado, $diasValidos))
                        <tr>
                            <td>{{ $dia }} {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                            <td class="text-center text-green">{{ $estadisticasPorDia[$dia]['Presente'] }}</td>
                            <td class="text-center text-red">{{ $estadisticasPorDia[$dia]['Ausente'] }}</td>
                            <td class="text-center text-yellow">{{ $estadisticasPorDia[$dia]['Justificado'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Detalles por participante -->
        <h2>Detalles por Participante</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombres y apellidos</th>
                    <th>Género</th>
                    <th>Grado</th>
                    <th>Programa</th>
                    @foreach ($diasSemana as $dia => $fecha)
                        @php
                            $diaNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], mb_strtolower($dia));
                            $diasValidos = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
                            $abreviaturas = [
                                'Lunes' => 'Lun', 'Martes' => 'Mar', 'Miércoles' => 'Mié',
                                'Jueves' => 'Jue', 'Viernes' => 'Vie',
                            ];
                            $diaAbreviado = $abreviaturas[$dia] ?? mb_substr($dia, 0, 3, 'UTF-8');
                        @endphp
                        @if (in_array($diaNormalizado, $diasValidos))
                            <th class="text-center">{{ $diaAbreviado }} {{ \Carbon\Carbon::parse($fecha)->format('d') }}</th>
                        @endif
                    @endforeach
                    <th class="text-center">Total</th>
                    <th class="text-center">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $participante)
                    <tr>
                        <td>{{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}</td>
                        <td>{{ $participante->genero }}</td>
                        <td>{{ $participante->grado_p ?? 'N/A' }}</td>
                        <td>{{ $participante->programa ?? 'N/A' }}</td>
                        @foreach ($diasSemana as $dia => $fecha)
                            @php
                                $diaNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], mb_strtolower($dia));
                            @endphp
                            @if (in_array($diaNormalizado, $diasValidos))
                                <td class="text-center">
                                    @php
                                        $estado = $asistencias[$participante->participante_id][$dia];
                                        $colorClass = match ($estado) {
                                            'Presente' => 'text-green',
                                            'Ausente' => 'text-red',
                                            'Justificado' => 'text-yellow',
                                            default => '',
                                        };
                                    @endphp
                                    <span class="{{ $colorClass }}">{{ substr($estado, 0, 1) }}</span>
                                </td>
                            @endif
                        @endforeach
                        <td class="text-center">{{ $participante->totalAsistido ?? 0 }}</td>
                        <td class="text-center">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay participantes inscritos.</p>
    @endif
</body>
</html>
