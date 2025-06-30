<?php

namespace App\Services;

use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class AsistenciaService
{
    public function getDiasParaAsistencia(Carbon $fechaReferencia, string $tipoAsistencia): array
    {
        $dias = [];
        $inicio = $tipoAsistencia === 'semanal' ? $fechaReferencia->copy()->startOfWeek() : $fechaReferencia;
        $rango = $tipoAsistencia === 'semanal' ? 5 : 1;

        for ($i = 0; $i < $rango; $i++) {
            $fecha = $inicio->copy()->addDays($i);
            $dias[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        return $dias;
    }

    public function cargarParticipantesYAsistencias($programa, $lugar, $grado, $fechaCarbon, $tipoAsistencia, $diasSemana)
    {
        $participantes = Participante::where('programa', $programa)
            ->where('lugar_de_encuentro_del_programa', $lugar)
            ->where('grado_p', $grado)
            ->get();

        $asistencias = [];

        foreach ($participantes as $p) {
            foreach ($diasSemana as $dia => $fecha) {
                $estado = DB::table('asistencias')
                    ->where('participante_id', $p->participante_id)
                    ->where('fecha_asistencia', $fecha)
                    ->value('estado') ?? 'Ausente';
                $asistencias[$p->participante_id][$dia] = $estado;
            }
        }

        return [$participantes, $asistencias];
    }

    public function getLugaresEncuentro(string $programa)
    {
        return Participante::where('programa', $programa)
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->select('lugar_de_encuentro_del_programa')
            ->distinct()
            ->pluck('lugar_de_encuentro_del_programa');
    }

    public function getGrados(string $programa, string $lugar)
    {
        return Participante::where('programa', $programa)
            ->where('lugar_de_encuentro_del_programa', $lugar)
            ->whereNotNull('grado_p')
            ->orderBy('grado_p', 'asc')
            ->select('grado_p')
            ->distinct()
            ->pluck('grado_p');
    }

    public function validateReporteRequest(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
            'grado_p' => 'nullable|string',
            'fecha' => 'required|date_format:Y-m-d',
            'tipo_asistencia' => 'required|in:semanal,diaria',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'response' => redirect()->back()
                    ->with('error', 'Errores en los filtros del formulario.')
                    ->withErrors($validator)
                    ->withInput()
            ];
        }

        $filters = $validator->validated();
        $fechaCarbon = Carbon::parse($filters['fecha']);

        if ($filters['tipo_asistencia'] === 'semanal' && $fechaCarbon->dayOfWeek !== Carbon::MONDAY) {
            return [
                'success' => false,
                'response' => redirect()->back()
                    ->with('error', 'Para reporte semanal, la fecha de inicio debe ser un lunes.')
                    ->withErrors(['fecha' => 'Debe ser lunes para asistencia semanal.'])
                    ->withInput()
            ];
        }

        return [
            'success' => true,
            'data' => compact('filters', 'fechaCarbon')
        ];
    }

    public function generarEstadisticasAsistencia(array $filters, array $diasSemana)
    {
        [$participantes, $asistencias] = $this->cargarParticipantesYAsistencias(
            $filters['programa'], $filters['lugar_de_encuentro_del_programa'],
            $filters['grado_p'], Carbon::parse($filters['fecha']), $filters['tipo_asistencia'], $diasSemana
        );

        $estadisticasPorDia = [];
        foreach ($diasSemana as $dia => $fecha) {
            $estadisticasPorDia[$dia] = ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Total' => 0];
        }

        foreach ($participantes as $p) {
            foreach ($diasSemana as $dia => $fecha) {
                $estado = $asistencias[$p->participante_id][$dia] ?? 'Ausente';
                if (isset($estadisticasPorDia[$dia][$estado])) $estadisticasPorDia[$dia][$estado]++;
                $estadisticasPorDia[$dia]['Total']++;
            }
        }

        $totalParticipantes = $participantes->count();
        $sumaPorcentajes = $participantes->sum('porcentajeAsistencia');
        $promedioAsistenciaGeneral = $totalParticipantes > 0
            ? round($sumaPorcentajes / $totalParticipantes)
            : 0;

        return [$participantes, $asistencias, $estadisticasPorDia, $promedioAsistenciaGeneral];
    }

    public function generarPDF(array $filters, $participantes, $diasSemana, $asistencias, $estadisticasPorDia, $promedioAsistenciaGeneral)
    {
        $fechaCarbon = Carbon::parse($filters['fecha']);
        $html = view('asistencia.pdf', compact(
            'filters', 'participantes', 'diasSemana', 'asistencias',
            'estadisticasPorDia', 'promedioAsistenciaGeneral'
        ))->render();

        try {
            $mpdf = new Mpdf([
                'format' => $filters['tipo_asistencia'] === 'semanal' ? 'A4-L' : 'A4',
                'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $mpdf->WriteHTML($html);
            $nombreArchivo = 'Reporte_Asistencia_' . ucfirst($filters['tipo_asistencia']) . '_' .
                             $filters['programa'] . '_' . $fechaCarbon->format('Y-m-d') . '.pdf';

            return $mpdf->Output($nombreArchivo, 'D');
        } catch (\Mpdf\MpdfException $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'No se pudo generar el PDF: ' . $e->getMessage())
                ->withInput();
        }
    }
}
