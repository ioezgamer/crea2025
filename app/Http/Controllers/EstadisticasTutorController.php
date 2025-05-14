<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EstadisticasTutorController extends Controller
{
    public function estadisticas(Request $request): View
    {
        $selectedProgram = $request->input('programa');
        $selectedPlace = $request->input('lugar');
        
        $query = Participante::query();

        if ($selectedProgram) {
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$selectedProgram]); // MySQL
            $query->where('programa', 'like', '%'. $selectedProgram .'%');
        }
        if ($selectedPlace) {
            $query->where('lugar_de_encuentro_del_programa', $selectedPlace); // Asumiendo que lugar es exacto o LIKE si es parcial
        }

        $programOptions = Participante::getDistinctProgramasOptions();
        $placeOptions = Participante::whereNotNull('lugar_de_encuentro_del_programa')
                                    ->distinct()
                                    ->pluck('lugar_de_encuentro_del_programa')
                                    ->filter()->sort()->values();

        $baseQueryForTutorStats = $query->clone()->whereNotNull('numero_de_cedula_tutor')->where('numero_de_cedula_tutor', '!=', '');

        $totalTutors = $baseQueryForTutorStats->clone()->distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');
        
        $tutorsByProgramData = $baseQueryForTutorStats->clone()
            ->select('programa', DB::raw('count(distinct numero_de_cedula_tutor) as count'))
            ->groupBy('programa')
            ->get();
        
        $tutorsByProgram = [];
        foreach($tutorsByProgramData as $item) {
            $programas = explode(',', $item->programa);
            foreach($programas as $prog) {
                $trimmedProg = trim($prog);
                if(empty($trimmedProg)) continue;
                if(!isset($tutorsByProgram[$trimmedProg])) $tutorsByProgram[$trimmedProg] = 0;
                // Esta lógica de conteo es aproximada si un tutor está en múltiples programas CSV y se cuenta por cada programa.
                // Para un conteo exacto de tutores por programa individual (cuando programa es CSV), se necesitaría un enfoque más complejo.
                // Lo más simple es que $item->count represente tutores cuya *fila* tiene ese programa (o CSV de programas).
                 $tutorsByProgram[$trimmedProg] += $item->count; // O una lógica más precisa si es necesaria.
            }
        }


        $tutorsBySector = $baseQueryForTutorStats->clone()->groupBy('sector_economico_tutor')
            ->selectRaw('sector_economico_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->whereNotNull('sector_economico_tutor')
            ->pluck('count', 'sector_economico_tutor')
            ->toArray();

        $tutorsByEducationLevel = $baseQueryForTutorStats->clone()->groupBy('nivel_de_educacion_formal_adquirido_tutor')
            ->selectRaw('nivel_de_educacion_formal_adquirido_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->whereNotNull('nivel_de_educacion_formal_adquirido_tutor')
            ->pluck('count', 'nivel_de_educacion_formal_adquirido_tutor')
            ->toArray();

        $tutorsByCommunity = $baseQueryForTutorStats->clone()->groupBy('comunidad_tutor')
            ->selectRaw('comunidad_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->whereNotNull('comunidad_tutor')
            ->pluck('count', 'comunidad_tutor')
            ->toArray();

        $totalParticipantsInFilter = $query->clone()->count();
        $averageParticipantsPerTutor = $totalTutors > 0 ? round($totalParticipantsInFilter / $totalTutors, 2) : 0;

        return view('tutores', compact( // Asegúrate que la vista 'tutores.blade.php' exista
            'totalTutors', 'tutorsByProgram', 'tutorsBySector',
            'tutorsByEducationLevel', 'tutorsByCommunity', 'averageParticipantsPerTutor',
            'programOptions', 'placeOptions', 'selectedProgram', 'selectedPlace'
        ));
    }

    public function participantesPorTutor(Request $request): View
    {
        $selectedProgram = $request->input('programa');
        $selectedPlace = $request->input('lugar');
        $query = Participante::query();

        if ($selectedProgram) {
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$selectedProgram]); // MySQL
            $query->where('programa', 'like', '%'. $selectedProgram .'%');
        }
        if ($selectedPlace) {
            $query->where('lugar_de_encuentro_del_programa', $selectedPlace);
        }

        $programOptions = Participante::getDistinctProgramasOptions();
        $placeOptions = Participante::whereNotNull('lugar_de_encuentro_del_programa')
                                    ->distinct()
                                    ->pluck('lugar_de_encuentro_del_programa')
                                    ->filter()->sort()->values();

        $participantesData = $query->select([
            'numero_de_cedula_tutor', 'nombres_y_apellidos_tutor_principal',
            'tutor_principal', 'programa', 'primer_nombre_p',
            'primer_apellido_p', 'grado_p'
        ])->where(function($q) { // Considerar solo tutores con cédula o nombre
            $q->whereNotNull('numero_de_cedula_tutor')->where('numero_de_cedula_tutor', '!=', '')
              ->orWhereNotNull('nombres_y_apellidos_tutor_principal')->where('nombres_y_apellidos_tutor_principal', '!=', '');
        })
        ->orderBy('nombres_y_apellidos_tutor_principal')->get();

        $tutors = [];
        foreach ($participantesData as $participante) {
            $tutorKey = !empty($participante->numero_de_cedula_tutor) 
                        ? $participante->numero_de_cedula_tutor 
                        : $participante->nombres_y_apellidos_tutor_principal;
            
            if (empty($tutorKey)) continue;

            if (!isset($tutors[$tutorKey])) {
                $tutors[$tutorKey] = [
                    'identificador_tutor' => $tutorKey,
                    'nombres_y_apellidos_tutor_principal' => $participante->nombres_y_apellidos_tutor_principal,
                    'tipos_tutor' => [], 
                    'programas_asociados_participantes' => [],
                    'participantes' => []
                ];
            }

            $tipoTutor = trim($participante->tutor_principal ?: 'No especificado');
            if (!in_array($tipoTutor, $tutors[$tutorKey]['tipos_tutor'])) {
                $tutors[$tutorKey]['tipos_tutor'][] = $tipoTutor;
            }
            
            // Programas de los participantes que este tutor supervisa
            $programasDelParticipante = explode(',', $participante->programa);
            foreach($programasDelParticipante as $progP) {
                $trimmedProgP = trim($progP);
                if(!empty($trimmedProgP) && !in_array($trimmedProgP, $tutors[$tutorKey]['programas_asociados_participantes'])) {
                    $tutors[$tutorKey]['programas_asociados_participantes'][] = $trimmedProgP;
                }
            }

            $tutors[$tutorKey]['participantes'][] = [
                'nombre_completo' => trim("{$participante->primer_nombre_p} {$participante->primer_apellido_p}"),
                'grado_p' => $participante->grado_p
            ];
        }
        
        foreach ($tutors as $key => $tutorData) {
            $tutors[$key]['tipos_tutor_str'] = implode(', ', array_unique($tutorData['tipos_tutor']));
            $tutors[$key]['programas_asociados_str'] = implode(', ', array_unique($tutorData['programas_asociados_participantes']));
        }

        return view('tutores_participantes', compact( // Asegúrate que la vista 'tutores_participantes.blade.php' exista
            'tutors', 'programOptions', 'placeOptions', 'selectedProgram', 'selectedPlace'
        ));
    }
}