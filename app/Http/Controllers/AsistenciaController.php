<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AsistenciaController extends Controller
{
    public function create(Request $request)
    {
        $selectedPrograma = $request->input('programa', '');
        $selectedLugar = $request->input('lugar_de_encuentro_del_programa', '');
        $selectedGrado = $request->input('grado_p', '');
        $selectedTipoAsistencia = $request->input('tipo_asistencia', 'semanal');

        // Set default date based on the selected attendance type
        $defaultDate = now();
        if ($selectedTipoAsistencia == 'semanal') {
            $defaultDate = $defaultDate->startOfWeek();
        } elseif ($selectedTipoAsistencia == 'mensual') {
            $defaultDate = $defaultDate->startOfMonth();
        }
        $fechaInput = $request->input('fecha', $defaultDate->format('Y-m-d'));
        $fechaCarbon = Carbon::parse($fechaInput);

        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = [];
        if ($selectedPrograma) {
            $lugarOptions = $this->getLugaresEncuentroQuery($selectedPrograma)->get()->pluck('lugar_de_encuentro_del_programa');
        }
        $gradoOptions = [];
        if ($selectedPrograma && $selectedLugar) {
            $gradoOptions = $this->getGradosQuery($selectedPrograma, $selectedLugar)->get()->pluck('grado_p');
        }
        $diasSemana = $this->getDiasParaAsistencia($fechaCarbon, $selectedTipoAsistencia);
        $participantes = collect();
        $asistencias = [];

        if ($selectedPrograma && $selectedLugar && $selectedGrado && $fechaInput) {
             list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
                $selectedPrograma, $selectedLugar, $selectedGrado, $fechaCarbon, $selectedTipoAsistencia, $diasSemana
            );
        }

        return view('asistencia.attendance', compact(
            'programOptions', 'lugarOptions', 'gradoOptions', 'selectedPrograma', 'selectedLugar',
            'selectedGrado', 'selectedTipoAsistencia', 'fechaInput', 'diasSemana', 'participantes', 'asistencias'
        ));
    }

    private function getDiasParaAsistencia(Carbon $fechaReferencia, string $tipoAsistencia): array
    {
        $dias = [];
        if ($tipoAsistencia === 'semanal') {
            $inicioSemana = $fechaReferencia->copy()->startOfWeek();
            for ($i = 0; $i < 5; $i++) { // Lunes a Viernes
                $fecha = $inicioSemana->copy()->addDays($i);
                $dias[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
            }
        } elseif ($tipoAsistencia === 'diaria') {
            $dias[$fechaReferencia->translatedFormat('l')] = $fechaReferencia->format('Y-m-d');
        } elseif ($tipoAsistencia === 'mensual') {
            $inicioMes = $fechaReferencia->copy()->startOfMonth();
            $finMes = $fechaReferencia->copy()->endOfMonth();
            for ($fecha = $inicioMes; $fecha->lte($finMes); $fecha->addDay()) {
                if ($fecha->isWeekday()) { // Only include weekdays (Monday to Friday)
                    // Use a unique key for the month view to avoid overwriting days with the same name
                    $dias[$fecha->translatedFormat('l') . ' ' . $fecha->format('d M')] = $fecha->format('Y-m-d');

                }
            }
        }
        return $dias;
    }

    private function cargarParticipantesYAsistencias($programa, $lugar, $grado, Carbon $fechaReferencia, $tipoAsistencia, $diasSemana)
    {
        $query = Participante::query()->where('activo', true);
        $query->where('programa', 'like', '%' . $programa . '%');
        if ($lugar) $query->where('lugar_de_encuentro_del_programa', $lugar);
        if ($grado) $query->where('grado_p', $grado);

        $fechaInicioRango = $fechaReferencia->copy();
        $fechaFinRango = $fechaReferencia->copy();

        if ($tipoAsistencia === 'semanal') {
            $fechaInicioRango = $fechaReferencia->copy()->startOfWeek();
            $fechaFinRango = $fechaReferencia->copy()->startOfWeek()->addDays(4); // Monday to Friday
        } elseif ($tipoAsistencia === 'mensual') {
            $fechaInicioRango = $fechaReferencia->copy()->startOfMonth();
            $fechaFinRango = $fechaReferencia->copy()->endOfMonth();
        }

        $participantes = $query->with(['asistencias' => function ($q) use ($fechaInicioRango, $fechaFinRango) {
            $q->whereBetween('fecha_asistencia', [$fechaInicioRango->format('Y-m-d'), $fechaFinRango->format('Y-m-d')]);
        }])->orderBy('primer_nombre_p')->orderBy('primer_apellido_p')->get();

        $asistenciasData = [];
        foreach ($participantes as $participante) {
            $asistenciasParticipante = $participante->asistencias->keyBy(fn($item) => Carbon::parse($item->fecha_asistencia)->format('Y-m-d'));
            $totalAsistido = 0;
            foreach ($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistenciasParticipante->get($fechaDia)?->estado ?? 'Ausente';
                $asistenciasData[$participante->participante_id][$diaNombre] = $estado;
                if ($estado === 'Presente') $totalAsistido++;
            }
            $participante->totalAsistido = $totalAsistido;
            $participante->porcentajeAsistencia = count($diasSemana) > 0 ? round(($totalAsistido / count($diasSemana)) * 100) : 0;
        }
        return [$participantes, $asistenciasData];
    }

    public function getParticipantesFiltrados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'required|string',
            'grado_p' => 'required|string',
            'fecha' => 'required|date_format:Y-m-d',
            'tipo_asistencia' => 'required|in:semanal,diaria,mensual',
        ]);
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->first(), 'details' => $validator->errors()], 400);

        $selectedPrograma = $request->input('programa');
        $selectedLugar = $request->input('lugar_de_encuentro_del_programa');
        $selectedGrado = $request->input('grado_p');
        $fechaInput = $request->input('fecha');
        $selectedTipoAsistencia = $request->input('tipo_asistencia');
        try {
            $fechaCarbon = Carbon::parse($fechaInput);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Formato de fecha inválido.'], 400);
        }
        $diasSemana = $this->getDiasParaAsistencia($fechaCarbon, $selectedTipoAsistencia);
        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $selectedPrograma, $selectedLugar, $selectedGrado, $fechaCarbon, $selectedTipoAsistencia, $diasSemana
        );
        if ($participantes->isEmpty()) {
             return response()->json(['html' => '<div class="p-6 mt-6 text-sm text-gray-500 bg-white rounded-lg shadow-sm">No se encontraron participantes con los filtros seleccionados.</div>']);
        }
        $htmlTabla = view('asistencia.partials.tabla_asistencia', compact(
            'participantes', 'diasSemana', 'asistencias', 'selectedPrograma', 'fechaInput', 'selectedLugar', 'selectedGrado', 'selectedTipoAsistencia'
        ))->render();
        return response()->json(['html' => $htmlTabla]);
    }

    private function getLugaresEncuentroQuery($programa)
    {
        $query = Participante::select('lugar_de_encuentro_del_programa')->distinct()
            ->whereNotNull('lugar_de_encuentro_del_programa')->where('lugar_de_encuentro_del_programa', '!=', '');
        if ($programa) $query->where('programa', 'like', '%' . $programa . '%');
        return $query->orderBy('lugar_de_encuentro_del_programa');
    }

    public function getLugaresEncuentro(Request $request)
    {
        $validator = Validator::make($request->all(), ['programa' => 'required|string']);
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 400);
        $programa = $request->query('programa');
        $lugares = $this->getLugaresEncuentroQuery($programa)->get()->pluck('lugar_de_encuentro_del_programa');
        return response()->json($lugares);
    }

    private function getGradosQuery($programa, $lugar)
    {
        $query = Participante::select('grado_p')->distinct()
            ->whereNotNull('grado_p')->where('grado_p', '!=', '');
        if ($programa) $query->where('programa', 'like', '%' . $programa . '%');
        if ($lugar) $query->where('lugar_de_encuentro_del_programa', $lugar);
        return $query->orderBy('grado_p');
    }

    public function getGrados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
        ]);
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->first()], 400);
        $programa = $request->query('programa');
        $lugar = $request->query('lugar_de_encuentro_del_programa');
        $grados = $this->getGradosQuery($programa, $lugar)->get()->pluck('grado_p');
        return response()->json($grados);
    }

  public function storeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'fecha_asistencia' => 'required|date_format:Y-m-d',
            'estado' => 'required|in:Presente,Ausente,Justificado',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $attributes = [
                'participante_id' => $request->input('participante_id'),
                'fecha_asistencia' => $request->input('fecha_asistencia'),
            ];

            $values = [
                'estado' => $request->input('estado'),
            ];

            DB::beginTransaction();

            $asistencia = DB::table('asistencias')->where($attributes)->first();

            if ($asistencia) {
                DB::table('asistencias')
                    ->where('id', $asistencia->id)
                    ->update(array_merge($values, ['updated_at' => now()]));
            } else {
                DB::table('asistencias')->insert(array_merge(
                    $attributes,
                    $values,
                    ['created_at' => now(), 'updated_at' => now()]
                ));
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Asistencia actualizada.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar asistencia individual: ' . $e->getMessage(), $request->all());
            return response()->json(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }

    public function reporte(Request $request)
    {
        $filtersValidator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
            'grado_p' => 'nullable|string',
            'fecha' => 'required|date_format:Y-m-d',
            'tipo_asistencia' => 'required|in:semanal,diaria,mensual',
        ]);

        if ($filtersValidator->fails()) {
            return redirect()->route('asistencia.create')
                ->with('error', 'Por favor corrige los errores del formulario para generar el reporte.')
                ->withErrors($filtersValidator)
                ->withInput();
        }
        $filters = $filtersValidator->validated();
        $fechaCarbon = Carbon::parse($filters['fecha']);

        $diasSemana = $this->getDiasParaAsistencia($fechaCarbon, $filters['tipo_asistencia']);
        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $filters['programa'], $filters['lugar_de_encuentro_del_programa'], $filters['grado_p'],
            $fechaCarbon, $filters['tipo_asistencia'], $diasSemana
        );

        $estadisticasPorDia = [];
        foreach($diasSemana as $diaNombre => $fechaDia) {
            $estadisticasPorDia[$diaNombre] = ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Total' => 0];
        }
        foreach($participantes as $p) {
            foreach($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistencias[$p->participante_id][$diaNombre] ?? 'Ausente';
                if (isset($estadisticasPorDia[$diaNombre][$estado])) $estadisticasPorDia[$diaNombre][$estado]++;
                $estadisticasPorDia[$diaNombre]['Total']++;
            }
        }
        $totalParticipantes = $participantes->count();
        $sumaPorcentajes = $participantes->sum('porcentajeAsistencia');
        $promedioAsistenciaGeneral = $totalParticipantes > 0 ? round($sumaPorcentajes / $totalParticipantes) : 0;

        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = $this->getLugaresEncuentroQuery($filters['programa'])->get()->pluck('lugar_de_encuentro_del_programa');
        $gradoOptions = $this->getGradosQuery($filters['programa'], $filters['lugar_de_encuentro_del_programa'])->get()->pluck('grado_p');

        return view('asistencia.reporte', compact(
            'participantes', 'asistencias', 'diasSemana', 'filters',
            'programOptions', 'lugarOptions', 'gradoOptions',
            'estadisticasPorDia', 'totalParticipantes', 'promedioAsistenciaGeneral'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filtersValidator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
            'grado_p' => 'nullable|string',
            'fecha' => 'required|date_format:Y-m-d',
            'tipo_asistencia' => 'required|in:semanal,diaria,mensual',
        ]);

        if ($filtersValidator->fails()) {
            return redirect()->route('asistencia.reporte')
                ->with('error', 'No se pudo generar el PDF debido a errores en los filtros.')
                ->withErrors($filtersValidator)
                ->withInput();
        }
        $filters = $filtersValidator->validated();
        $fechaCarbon = Carbon::parse($filters['fecha']);

        $diasSemana = $this->getDiasParaAsistencia($fechaCarbon, $filters['tipo_asistencia']);
        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $filters['programa'], $filters['lugar_de_encuentro_del_programa'], $filters['grado_p'],
            $fechaCarbon, $filters['tipo_asistencia'], $diasSemana
        );
        $estadisticasPorDia = [];
        foreach($diasSemana as $diaNombre => $fechaDia) $estadisticasPorDia[$diaNombre] = ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Total' => 0];
        foreach($participantes as $p) {
            foreach($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistencias[$p->participante_id][$diaNombre] ?? 'Ausente';
                 if (isset($estadisticasPorDia[$diaNombre][$estado])) $estadisticasPorDia[$diaNombre][$estado]++;
                $estadisticasPorDia[$diaNombre]['Total']++;
            }
        }
        $totalParticipantes = $participantes->count();
        $sumaPorcentajes = $participantes->sum('porcentajeAsistencia');
        $promedioAsistenciaGeneral = $totalParticipantes > 0 ? round($sumaPorcentajes / $totalParticipantes) : 0;

        $html = view('asistencia.pdf', compact(
            'filters', 'participantes', 'diasSemana', 'asistencias',
            'totalParticipantes', 'promedioAsistenciaGeneral', 'estadisticasPorDia'
        ))->render();

        try {
            $mpdf = new \Mpdf\Mpdf([
                'format' => in_array($filters['tipo_asistencia'], ['semanal', 'mensual']) ? 'A4-L' : 'A4',
                'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 10, 'margin_right' => 10,
            ]);
            $mpdf->WriteHTML($html);
            $fechaFormateada = $fechaCarbon->format('Y-m-d');
            $tipoReporte = ucfirst($filters['tipo_asistencia']);
            $nombreArchivo = "Reporte_Asistencia_{$tipoReporte}_{$filters['programa']}_{$fechaFormateada}.pdf";
            return $mpdf->Output($nombreArchivo, 'D');
        } catch (\Mpdf\MpdfException $e) {
            Log::error("Error al generar PDF de asistencia: " . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'No se pudo generar el PDF: ' . $e->getMessage())
                            ->withInput($request->all());
        }
    }
}
