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

        // Consulta de participantes con filtros antes del get()
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

        // Obtener lugar de encuentro del primer participante encontrado (para compatibilidad con vista existente)
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
                $asistencias[$participante->participante_id][$dia] = match ((int) $estado) {
                    1 => 'Presente',
                    0 => 'Ausente',
                    2 => 'Justificado',
                    default => 'Ausente',
                };
            }

            $totalAsistido = $asistenciasParticipante->where('estado', 1)->count();
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
            'asistencias' => 'required|array',
            'asistencias.*' => 'array',
            'asistencias.*.*' => 'in:Presente,Ausente,Justificado',
        ]);

        $programa = $request->input('programa');
        $fechaInicio = Carbon::parse($request->input('fecha_inicio'));
        $asistencias = $request->input('asistencias');

        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicio->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        DB::beginTransaction();
        try {
            Asistencia::whereIn('participante_id', array_keys($asistencias))
                ->whereBetween('fecha_asistencia', [$fechaInicio, $fechaInicio->copy()->addDays(4)])
                ->delete();

            foreach ($asistencias as $participanteId => $dias) {
                foreach ($dias as $dia => $estado) {
                    if (!isset($diasSemana[$dia])) {
                        throw new \Exception("Día inválido: {$dia}");
                    }

                    $fecha = $diasSemana[$dia];
                    $valorEstado = match ($estado) {
                        'Presente' => 1,
                        'Ausente' => 0,
                        'Justificado' => 2,
                        default => throw new \Exception("Estado inválido: {$estado}"),
                    };

                    Asistencia::create([
                        'participante_id' => (int) $participanteId,
                        'fecha_asistencia' => $fecha,
                        'estado' => $valorEstado,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('asistencia.create', [
                'programa' => $programa,
                'fecha_inicio' => $fechaInicio->format('Y-m-d')
            ])->with('success', 'Asistencia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al registrar asistencia: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Error al registrar la asistencia: ' . $e->getMessage()])
                ->withInput();
        }
    }
}