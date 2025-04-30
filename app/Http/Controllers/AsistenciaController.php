<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Configurar el idioma para Carbon
        Carbon::setLocale('es');

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

        // Generar los días de lunes a viernes (almacenar fechas completas con hora 00:00:00)
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i)->startOfDay();
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->toDateTimeString();
        }

        $asistencias = [];
        foreach ($participantes as $participante) {
            $asistenciasParticipante = Asistencia::where('participante_id', $participante->participante_id)
                ->whereBetween('fecha_asistencia', [$fechaInicioCarbon, $fechaInicioCarbon->copy()->addDays(4)->endOfDay()])
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->fecha_asistencia)->toDateTimeString();
                });

            // Verificar si el participante tiene asistencias guardadas para todos los días
            $participante->hasAsistenciasGuardadas = $asistenciasParticipante->count() === count($diasSemana);

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

        // Configurar el idioma para Carbon
        Carbon::setLocale('es');

        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicio->copy()->addDays($i)->startOfDay();
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->toDateTimeString();
        }

        DB::beginTransaction();
        try {
            // Eliminar asistencias existentes para este participante en la semana seleccionada
            Asistencia::where('participante_id', $participanteId)
                ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaInicio->copy()->addDays(4)->endOfDay()])
                ->delete();

            // Guardar las nuevas asistencias
            foreach ($asistencias[$participanteId] as $dia => $estado) {
                if (!isset($diasSemana[$dia])) {
                    throw new \Exception("Día inválido: {$dia}");
                }

                $fecha = $diasSemana[$dia];
                if (!in_array($estado, ['Presente', 'Ausente', 'Justificado'])) {
                    throw new \Exception("Estado inválido: {$estado}");
                }

                Asistencia::create([
                    'participante_id' => $participanteId,
                    'fecha_asistencia' => $fecha,
                    'estado' => $estado,
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

        $fechaInicioCarbon = Carbon::parse($fechaInicio);
        if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
            return redirect()->route('asistencia.reporte')
                ->withErrors(['fecha_inicio' => 'La fecha de inicio debe ser un lunes.'])
                ->withInput();
        }

        // Configurar el idioma para Carbon
        Carbon::setLocale('es');

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

        // Generar los días de lunes a viernes (almacenar fechas completas con hora 00:00:00)
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i)->startOfDay();
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->toDateTimeString();
        }

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

            $asistenciasParticipante = Asistencia::where('participante_id', $participante->participante_id)
                ->whereBetween('fecha_asistencia', [$fechaInicioCarbon, $fechaInicioCarbon->copy()->addDays(4)->endOfDay()])
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->fecha_asistencia)->toDateTimeString();
                });

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
}