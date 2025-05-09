<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class AsistenciaController extends Controller
{
    public function create(Request $request)
    {
        $programa = $request->input('programa', '');
        $lugarencuentro = $request->input('lugar_de_encuentro_del_programa', '');
        $grado = $request->input('grado_p', '');
        $fechaInicio = $request->input('fecha_inicio', now()->startOfWeek()->format('Y-m-d'));
    
        $fechaInicioCarbon = Carbon::parse($fechaInicio);
        if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
            return redirect()->route('asistencia.create')
                ->withErrors(['fecha_inicio' => 'La fecha de inicio debe ser un lunes.'])
                ->withInput();
        }
    
        // Consulta de participantes con filtros
        $query = Participante::query();
        if ($programa) {
            $query->where('programa', 'LIKE', '%' . $programa . '%');
        }
        if ($lugarencuentro) {
            $query->where('lugar_de_encuentro_del_programa', 'LIKE', '%' . $lugarencuentro . '%');
        }
        if ($grado) {
            $query->where('grado_p', 'LIKE', '%' . $grado . '%');
        }
        $participantes = $query->get();
    
        // Obtener lugares de encuentro únicos
        $lugares_encuentro = Participante::select('lugar_de_encuentro_del_programa')
            ->distinct()
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->pluck('lugar_de_encuentro_del_programa')
            ->sort()
            ->values();
    
        // Obtener grados únicos
        $grados = Participante::select('grado_p')
            ->distinct()
            ->whereNotNull('grado_p')
            ->pluck('grado_p')
            ->sort()
            ->values();
    
        // Obtener lugar de encuentro del primer participante encontrado
        $lugar_encuentro = $participantes->first()?->lugar_de_encuentro_del_programa;
    
        // Generar los días de lunes a viernes
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }
    
        $asistencias = [];
        foreach ($participantes as $participante) {
            $asistenciasParticipante = Asistencia::where('participante_id', $participante->participante_id)
                ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaInicioCarbon->copy()->addDays(4)])
                ->get()
                ->keyBy('fecha_asistencia');
    
            foreach ($diasSemana as $dia => $fecha) {
                $estado = $asistenciasParticipante->get($fecha)?->estado;
                $asistencias[$participante->participante_id][$dia] = $estado ?? 'Ausente';
            }
    
            $totalAsistido = $asistenciasParticipante->where('estado', 'Presente')->count();
            $participante->totalAsistido = $totalAsistido;
            $participante->porcentajeAsistencia = count($diasSemana) > 0
                ? ($totalAsistido / count($diasSemana)) * 100
                : 0;
        }
    
        return view('asistencia.attendance', compact(
            'participantes',
            'programa',
            'fechaInicio',
            'diasSemana',
            'asistencias',
            'lugar_encuentro',
            'lugares_encuentro',
            'grado',
            'grados'
        ));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'programa' => 'required|string',
        'fecha_inicio' => 'required|date',
        'lugar_de_encuentro_del_programa' => 'nullable|string',
        'grado_p' => 'nullable|string',
        'participante_id' => 'required|integer|exists:participantes,participante_id',
        'asistencias' => 'required|array',
        'asistencias.*' => 'array',
        'asistencias.*.*' => 'in:Presente,Ausente,Justificado',
    ]);

    $programa = $request->input('programa');
    $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
    $lugarEncuentro = $request->input('lugar_de_encuentro_del_programa');
    $grado = $request->input('grado_p');
    $participanteId = $request->input('participante_id');
    $asistencias = $request->input('asistencias');

    $diasSemana = [];
    for ($i = 0; $i < 5; $i++) {
        $fecha = $fechaInicio->copy()->addDays($i);
        $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
    }

    DB::beginTransaction();
    try {
        // Eliminar asistencias existentes para este participante en la semana seleccionada
        Asistencia::where('participante_id', $participanteId)
            ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaInicio->copy()->addDays(4)])
            ->delete();

        // Guardar las nuevas asistencias
        foreach ($asistencias[$participanteId] as $dia => $estado) {
            if (!isset($diasSemana[$dia])) {
                throw new \Exception("Día inválido: {$dia}");
            }

            $fecha = $diasSemana[$dia];
            // Validar que el estado sea válido
            if (!in_array($estado, ['Presente', 'Ausente', 'Justificado'])) {
                throw new \Exception("Estado inválido: {$estado}");
            }

            Asistencia::create([
                'participante_id' => $participanteId,
                'fecha_asistencia' => $fecha,
                'estado' => $estado, // Usar la cadena directamente
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();
        return redirect()->route('asistencia.create', [
            'programa' => $programa,
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'lugar_de_encuentro_del_programa' => $lugarEncuentro,
            'grado_p' => $grado,
        ])->with('success', 'Asistencia registrada correctamente para el participante.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al registrar asistencia: ' . $e->getMessage());
        return redirect()->back()
            ->withErrors(['error' => 'Error al registrar la asistencia: ' . $e->getMessage()])
            ->withInput();
    }
}

    public function reporte(Request $request)
    {
        $programa = $request->input('programa', '');
        $lugarencuentro = $request->input('lugar_de_encuentro_del_programa', '');
        $grado = $request->input('grado_p', '');
        $fechaInicio = $request->input('fecha_inicio', now()->startOfWeek()->format('Y-m-d'));

        // Configurar idioma
        Carbon::setLocale('es');
        $fechaInicioCarbon = Carbon::parse($fechaInicio);

        if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
            return redirect()->route('asistencia.reporte')
                ->withErrors(['fecha_inicio' => 'La fecha de inicio debe ser un lunes.'])
                ->withInput();
        }

        // Log de los parámetros recibidos
        \Log::info('Parámetros recibidos en reporte', [
            'programa' => $programa,
            'lugar_de_encuentro_del_programa' => $lugarencuentro,
            'grado_p' => $grado,
            'fecha_inicio' => $fechaInicio,
        ]);

        // Consulta de participantes con filtros
        $query = Participante::query();
        if ($programa) {
            $query->where('programa', 'LIKE', '%' . $programa . '%');
        }
        if ($lugarencuentro) {
            $query->where('lugar_de_encuentro_del_programa', 'LIKE', '%' . $lugarencuentro . '%');
        }
        if ($grado) {
            $query->where('grado_p', 'LIKE', '%' . $grado . '%');
        }
        $participantes = $query->get();

        // Log para depurar los participantes cargados
        \Log::info('Participantes cargados en reporte', [
            'participantes' => $participantes->map(function ($p) {
                return [
                    'id' => $p->participante_id,
                    'nombre' => $p->primer_nombre_p . ' ' . $p->primer_apellido_p,
                ];
            })->toArray(),
        ]);

        // Obtener lugares de encuentro únicos
        $lugares_encuentro = Participante::select('lugar_de_encuentro_del_programa')
            ->distinct()
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->pluck('lugar_de_encuentro_del_programa')
            ->sort()
            ->values();

        // Obtener grados únicos
        $grados = Participante::select('grado_p')
            ->distinct()
            ->whereNotNull('grado_p')
            ->pluck('grado_p')
            ->sort()
            ->values();

        // Obtener lugar de encuentro del primer participante encontrado
        $lugar_encuentro = $participantes->first()?->lugar_de_encuentro_del_programa;

        // Generar los días de lunes a viernes (solo fecha, sin hora)
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->toDateString();
        }

        \Log::info('Días de la semana generados en reporte', ['diasSemana' => $diasSemana]);

        // Validar que $diasSemana no esté vacío
        if (empty($diasSemana)) {
            \Log::error('No se pudieron generar los días de la semana', ['fecha_inicio' => $fechaInicio]);
            return redirect()->back()->withErrors(['error' => 'No se pudieron generar los días de la semana.']);
        }

        $asistencias = [];
        $estadisticasPorDia = array_fill_keys(array_keys($diasSemana), ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0]);
        foreach ($participantes as $participante) {
            // Validar que participante_id sea válido
            if (empty($participante->participante_id)) {
                \Log::warning('Participante sin ID válido', ['participante' => $participante->toArray()]);
                continue;
            }

            $fechaInicioStr = $fechaInicioCarbon->toDateString();
            $fechaFinStr = $fechaInicioCarbon->copy()->addDays(4)->toDateString();

            $asistenciasParticipante = Asistencia::where('participante_id', $participante->participante_id)
                ->whereBetween('fecha_asistencia', [$fechaInicioStr, $fechaFinStr])
                ->get()
                ->keyBy('fecha_asistencia');

            // Log para depurar las asistencias cargadas
            \Log::info('Asistencias cargadas para participante en reporte', [
                'participante_id' => $participante->participante_id,
                'nombre' => $participante->primer_nombre_p . ' ' . $participante->primer_apellido_p,
                'asistencias' => $asistenciasParticipante->toArray(),
                'rango' => [$fechaInicioStr, $fechaFinStr],
                'query' => "SELECT * FROM asistencias WHERE participante_id = {$participante->participante_id} AND fecha_asistencia BETWEEN '$fechaInicioStr' AND '$fechaFinStr'",
            ]);

            // Inicializar el array de asistencias para este participante
            $asistencias[$participante->participante_id] = [];

            foreach ($diasSemana as $dia => $fecha) {
                $estado = $asistenciasParticipante->get($fecha)?->estado ?? 'Ausente';
                $asistencias[$participante->participante_id][$dia] = $estado;

                // Validar que las claves existan antes de incrementar
                if (isset($estadisticasPorDia[$dia]) && isset($estadisticasPorDia[$dia][$estado])) {
                    $estadisticasPorDia[$dia][$estado]++;
                } else {
                    \Log::warning('Clave no encontrada en estadisticasPorDia', [
                        'dia' => $dia,
                        'estado' => $estado,
                        'estadisticasPorDia' => $estadisticasPorDia,
                    ]);
                }
            }

            $totalAsistido = $asistenciasParticipante->where('estado', 'Presente')->count();
            $participante->totalAsistido = $totalAsistido;
            $participante->porcentajeAsistencia = count($diasSemana) > 0
                ? ($totalAsistido / count($diasSemana)) * 100
                : 0;
        }

        // Calcular estadísticas generales
        $totalParticipantes = $participantes->count();
        $promedioAsistencia = $totalParticipantes > 0
            ? ($participantes->sum('totalAsistido') / ($totalParticipantes * count($diasSemana))) * 100
            : 0;

        return view('asistencia.reporte', compact(
            'participantes',
            'programa',
            'fechaInicio',
            'diasSemana',
            'asistencias',
            'lugar_encuentro',
            'lugares_encuentro',
            'grado',
            'grados',
            'estadisticasPorDia',
            'totalParticipantes',
            'promedioAsistencia'
        ));
    }

    public function exportPdf(Request $request)
{
    // Obtener los filtros de la solicitud
    $programa = $request->query('programa');
    $fechaInicio = $request->query('fecha_inicio', now()->startOfWeek()->format('Y-m-d'));
    $lugar_encuentro = $request->query('lugar_de_encuentro_del_programa');
    $grado = $request->query('grado_p');

    // Validar que el programa esté seleccionado
    if (!$programa) {
        return redirect()->route('asistencia.reporte')->withErrors(['programa' => 'El programa es obligatorio']);
    }

    // Obtener participantes con los filtros aplicados
    $query = Participante::query()->where('programa', $programa);

    if ($lugar_encuentro) {
        $query->where('lugar_de_encuentro_del_programa', $lugar_encuentro);
    }

    if ($grado) {
        $query->where('grado_p', $grado);
    }

    $participantes = $query->get();

    // Definir los días de la semana (lunes a viernes) a partir de la fecha de inicio
    $startDate = Carbon::parse($fechaInicio)->startOfWeek();
    $diasSemana = [];
    for ($i = 0; $i < 5; $i++) {
        $fecha = $startDate->copy()->addDays($i);
        $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
    }

    // Log para depurar los días generados
    \Log::info('Días de la semana en exportPdf', ['diasSemana' => $diasSemana]);

    // Obtener asistencias
    $asistencias = [];
    $estadisticasPorDia = [];
    foreach ($participantes as $participante) {
        $asistencias[$participante->participante_id] = [];
        $totalAsistido = 0;

        foreach ($diasSemana as $dia => $fecha) {
            $asistencia = Asistencia::where('participante_id', $participante->participante_id)
                ->whereDate('fecha_asistencia', $fecha)
                ->first();

            $estado = $asistencia ? $asistencia->estado : 'Ausente';
            $asistencias[$participante->participante_id][$dia] = $estado;

            if ($estado === 'Presente') {
                $totalAsistido++;
            }

            // Inicializar estadísticas por día
            if (!isset($estadisticasPorDia[$dia])) {
                $estadisticasPorDia[$dia] = [
                    'Presente' => 0,
                    'Ausente' => 0,
                    'Justificado' => 0,
                ];
            }
            $estadisticasPorDia[$dia][$estado]++;
        }

        $participante->totalAsistido = $totalAsistido;
        $participante->porcentajeAsistencia = count($diasSemana) > 0 ? ($totalAsistido / count($diasSemana)) * 100 : 0;
    }

    // Calcular estadísticas generales
    $totalParticipantes = $participantes->count();
    $promedioAsistencia = $totalParticipantes > 0
        ? $participantes->sum('porcentajeAsistencia') / $totalParticipantes
        : 0;

    // Generar el HTML para el PDF (usando una vista)
    $html = view('asistencia.pdf', compact(
        'programa',
        'fechaInicio',
        'lugar_encuentro',
        'grado',
        'participantes',
        'diasSemana',
        'asistencias',
        'totalParticipantes',
        'promedioAsistencia',
        'estadisticasPorDia'
    ))->render();

    // Configurar mPDF
    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4-L', // Formato horizontal para la tabla
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
    ]);

    // Escribir el HTML en el PDF
    $mpdf->WriteHTML($html);

    // Descargar el PDF
    $fechaFormateada = Carbon::parse($fechaInicio)->format('Y-m-d');
    $nombreArchivo = "Reporte_Asistencias_{$programa}_{$fechaFormateada}.pdf";
    return $mpdf->Output($nombreArchivo, 'D'); // 'D' para descargar directamente
}
}