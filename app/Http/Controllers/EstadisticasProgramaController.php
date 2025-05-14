<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstadisticasProgramaController extends Controller
{
    public function index(Request $request): View
    {
        $selectedProgramFilter = $request->input('programa'); 
        $query = Participante::query();

        $programOptions = Participante::getDistinctProgramasOptions(); // Reutilizar método del modelo

        if ($selectedProgramFilter) {
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$selectedProgramFilter]); // MySQL
            $query->where('programa', 'like', '%'. $selectedProgramFilter .'%'); // Alternativa
        }

        $totalParticipantsInFilter = $query->count();
        
        // Las siguientes estadísticas se calcularán sobre el conjunto filtrado por $selectedProgramFilter
        $participantsByGrade = $query->clone()->groupBy('grado_p')
            ->selectRaw('grado_p, count(*) as count')
            ->pluck('count', 'grado_p')
            ->toArray();

        $participantsByGender = $query->clone()->groupBy('genero')
            ->selectRaw('genero, count(*) as count')
            ->pluck('count', 'genero')
            ->toArray();

        $averageAge = $query->avg('edad_p');

        $participantsByAgeGroup = $query->clone()->selectRaw("
            CASE 
                WHEN edad_p < 10 THEN 'Menor a 10 años'
                WHEN edad_p BETWEEN 10 AND 14 THEN '10-14 años'
                WHEN edad_p BETWEEN 15 AND 18 THEN '15-18 años'
                ELSE 'Mayor a 18 años'
            END as age_group, 
            count(*) as count")
            ->groupBy('age_group')
            ->pluck('count', 'age_group')
            ->toArray();
            
        // Para participantsBySubProgram (campo 'programas' que es CSV)
        // Necesitarías una lógica de procesamiento similar a la de DashboardController para programCounts
        $allSubProgramEntries = $query->clone()->whereNotNull('programas')->pluck('programas');
        $subProgramCounts = [];
        foreach ($allSubProgramEntries as $subProgramCsv) {
            $individualSubPrograms = explode(',', $subProgramCsv);
            foreach ($individualSubPrograms as $subProg) {
                $trimmedSubProg = trim($subProg);
                if (!empty($trimmedSubProg)) {
                    if (!isset($subProgramCounts[$trimmedSubProg])) {
                        $subProgramCounts[$trimmedSubProg] = 0;
                    }
                    $subProgramCounts[$trimmedSubProg]++;
                }
            }
        }
        arsort($subProgramCounts);
        $participantsBySubProgram = $subProgramCounts;


        return view('programas', compact( // Asegúrate que la vista 'programas.blade.php' exista
            'totalParticipantsInFilter',
            'participantsByGrade',
            'participantsByGender',
            'averageAge',
            'participantsByAgeGroup',
            'programOptions', 
            'selectedProgramFilter', // Renombrado para claridad
            'participantsBySubProgram'
        ));
    }
}
