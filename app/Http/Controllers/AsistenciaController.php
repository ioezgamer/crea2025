<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Services\AsistenciaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsistenciaController extends Controller
{
    protected AsistenciaService $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }

    public function create(Request $request)
    {
        $selectedPrograma = $request->input('programa', '');
        $selectedLugar = $request->input('lugar_de_encuentro_del_programa', '');
        $selectedGrado = $request->input('grado_p', '');
        $selectedTipoAsistencia = $request->input('tipo_asistencia', 'semanal');
        $fechaInput = $request->input('fecha', $selectedTipoAsistencia == 'semanal' ? now()->startOfWeek()->format('Y-m-d') : now()->format('Y-m-d'));
        $fechaCarbon = Carbon::parse($fechaInput);

        if ($selectedTipoAsistencia == 'semanal' && $fechaCarbon->dayOfWeek !== Carbon::MONDAY) {
            return redirect()->route('asistencia.create', $request->except('fecha'))
                ->with('error', 'Para asistencia semanal, la fecha de inicio debe ser un lunes.')
                ->withErrors(['fecha' => 'Para asistencia semanal, la fecha de inicio debe ser un lunes.'])
                ->withInput();
        }

        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = $selectedPrograma ? $this->asistenciaService->getLugaresEncuentro($selectedPrograma) : [];
        $gradoOptions = ($selectedPrograma && $selectedLugar) ? $this->asistenciaService->getGrados($selectedPrograma, $selectedLugar) : [];

        $diasSemana = $this->asistenciaService->getDiasParaAsistencia($fechaCarbon, $selectedTipoAsistencia);
        $participantes = collect();
        $asistencias = [];

        if ($selectedPrograma && $selectedLugar && $selectedGrado && $fechaInput) {
            list($participantes, $asistencias) = $this->asistenciaService->cargarParticipantesYAsistencias(
                $selectedPrograma, $selectedLugar, $selectedGrado, $fechaCarbon, $selectedTipoAsistencia, $diasSemana
            );
        }

        return view('asistencia.attendance', compact(
            'programOptions', 'lugarOptions', 'gradoOptions', 'selectedPrograma', 'selectedLugar',
            'selectedGrado', 'selectedTipoAsistencia', 'fechaInput', 'diasSemana', 'participantes', 'asistencias'
        ));
    }

    public function reporte(Request $request)
    {
        $validated = $this->asistenciaService->validateReporteRequest($request);
        if (!$validated['success']) return $validated['response'];

        extract($validated['data']);
        $diasSemana = $this->asistenciaService->getDiasParaAsistencia($fechaCarbon, $filters['tipo_asistencia']);

        [$participantes, $asistencias, $estadisticasPorDia, $promedioAsistenciaGeneral] =
            $this->asistenciaService->generarEstadisticasAsistencia($filters, $diasSemana);

        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = $this->asistenciaService->getLugaresEncuentro($filters['programa']);
        $gradoOptions = $this->asistenciaService->getGrados($filters['programa'], $filters['lugar_de_encuentro_del_programa']);

        return view('asistencia.reporte', compact(
            'participantes', 'asistencias', 'diasSemana', 'filters',
            'programOptions', 'lugarOptions', 'gradoOptions', 'estadisticasPorDia',
            'promedioAsistenciaGeneral'
        ));
    }

    public function exportPdf(Request $request)
    {
        $validated = $this->asistenciaService->validateReporteRequest($request);
        if (!$validated['success']) return $validated['response'];

        extract($validated['data']);
        $diasSemana = $this->asistenciaService->getDiasParaAsistencia($fechaCarbon, $filters['tipo_asistencia']);

        [$participantes, $asistencias, $estadisticasPorDia, $promedioAsistenciaGeneral] =
            $this->asistenciaService->generarEstadisticasAsistencia($filters, $diasSemana);

        return $this->asistenciaService->generarPDF($filters, $participantes, $diasSemana, $asistencias, $estadisticasPorDia, $promedioAsistenciaGeneral);
    }
}
