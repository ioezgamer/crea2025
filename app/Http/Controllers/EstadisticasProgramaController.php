<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EstadisticasProgramaController extends Controller
{
    public function index(Request $request): View
    {
        $selectedProgram = $request->input('programa');
        $selectedLugar = $request->input('lugar');

        // Query base para participantes
        $query = Participante::query();

        // Aplicar filtros
        if ($selectedProgram) {
            $query->where('programa', 'like', '%' . $selectedProgram . '%');
        }
        if ($selectedLugar) {
            $query->where('lugar_de_encuentro_del_programa', $selectedLugar);
        }

        // === Obtención de Datos para Estadísticas ===
        $totalParticipantsInFilter = $query->count();
        $averageAge = round($query->avg('edad_p'), 1);

        $participantsByGrade = $query->clone()->groupBy('grado_p')
            ->selectRaw('grado_p, count(*) as count')
            ->whereNotNull('grado_p')->where('grado_p', '!=', '')
            ->orderBy('grado_p')
            ->pluck('count', 'grado_p');

        $participantsByGender = $query->clone()->groupBy('genero')
            ->selectRaw("CASE WHEN genero = 'Masculino' THEN 'Masculino' WHEN genero = 'Femenino' THEN 'Femenino' ELSE 'No especificado' END as gender_label, count(*) as count")
            ->whereNotNull('genero')->where('genero', '!=', '')
            ->pluck('count', 'gender_label');

        $participantsByAgeGroup = $query->clone()->selectRaw(
            "CASE
                WHEN edad_p < 10 THEN 'Menor a 10'
                WHEN edad_p BETWEEN 10 AND 12 THEN '10-12 años'
                WHEN edad_p BETWEEN 13 AND 15 THEN '13-15 años'
                WHEN edad_p BETWEEN 16 AND 18 THEN '16-18 años'
                ELSE 'Mayor a 18'
            END as age_group, count(*) as count"
        )
        ->whereNotNull('edad_p')
        ->groupBy('age_group')
        ->orderByRaw("MIN(edad_p)")
        ->pluck('count', 'age_group');

        $allSubProgramEntries = $query->clone()->whereNotNull('programa')->where('programa', '!=', '')->pluck('programa');
        $subProgramCounts = collect($allSubProgramEntries)
            ->flatMap(fn ($csv) => explode(',', $csv))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->countBy();
        $participantsBySubProgram = $subProgramCounts->sortDesc();

        // === Obtención de Datos para los Filtros Dropdown ===
        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = [];
        if ($selectedProgram) {
            $lugarOptions = Participante::where('programa', 'like', '%' . $selectedProgram . '%')
                ->distinct()
                ->whereNotNull('lugar_de_encuentro_del_programa')
                ->orderBy('lugar_de_encuentro_del_programa')
                ->pluck('lugar_de_encuentro_del_programa')
                ->toArray();
        }

        return view('programas', compact(
            'totalParticipantsInFilter',
            'averageAge',
            'participantsByGrade',
            'participantsByGender',
            'participantsByAgeGroup',
            'participantsBySubProgram',
            'programOptions',
            'lugarOptions',
            'selectedProgram',
            'selectedLugar'
        ));
    }

    public function getLugaresForPrograma(Request $request): JsonResponse
    {
        $request->validate(['programa' => 'required|string']);
        $programa = $request->input('programa');

        $lugares = Participante::where('programa', 'like', '%' . $programa . '%')
            ->distinct()
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->orderBy('lugar_de_encuentro_del_programa')
            ->pluck('lugar_de_encuentro_del_programa');

        return response()->json($lugares);
    }
}
