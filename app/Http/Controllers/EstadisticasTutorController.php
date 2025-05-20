<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\View\View; // Importante para el type hinting
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Log sigue siendo útil

class EstadisticasTutorController extends Controller
{
    // Método para obtener opciones de programa, similar a como lo tenías.
    // Asumimos que Participante::getDistinctProgramasOptions() existe y funciona.
    private function getProgramOptions() {
        return Participante::getDistinctProgramasOptions();
    }

    // Método para obtener opciones de lugar, considerando el programa seleccionado para el filtro dinámico.
    private function getPlaceOptions($selectedProgram = null) {
        $query = Participante::whereNotNull('lugar_de_encuentro_del_programa')
                             ->where('lugar_de_encuentro_del_programa', '!=', '');
        if ($selectedProgram) {
            // Si se seleccionó un programa, filtramos los lugares que corresponden a ese programa.
            // Usamos LIKE porque 'programa' puede ser una lista CSV y no tenemos FIND_IN_SET en todos los SQL.
            $query->where('programa', 'like', '%' . $selectedProgram . '%');
        }
        return $query->distinct()
                      ->pluck('lugar_de_encuentro_del_programa')
                      ->filter()->sort()->values();
    }

    public function estadisticas(Request $request): View
    {
        $selectedProgram = $request->input('programa');
        $selectedPlace = $request->input('lugar');
        
        // Query base para participantes, se irá filtrando.
        $query = Participante::query();

        if ($selectedProgram) {
            // Usamos LIKE ya que 'programa' puede ser un CSV.
            // FIND_IN_SET es específico de MySQL. LIKE es más general.
            $query->where('programa', 'like', '%'. $selectedProgram .'%');
        }
        if ($selectedPlace) {
            // Asumimos que el lugar es una coincidencia exacta o se ajusta si es necesario.
            $query->where('lugar_de_encuentro_del_programa', $selectedPlace);
        }

        // Opciones para los dropdowns de filtro
        $programOptions = $this->getProgramOptions(); // Usando el método privado
        // Los lugares se cargan inicialmente (todos o filtrados si ya hay un programa en la URL)
        $placeOptions = $this->getPlaceOptions($selectedProgram);


        // Query base para estadísticas de tutores, aplicando los filtros de programa/lugar
        // y asegurando que el tutor tenga un identificador.
        // Usaremos 'numero_de_cedula_tutor' como identificador principal del tutor.
        $baseQueryForTutorStats = $query->clone()
                                       ->whereNotNull('numero_de_cedula_tutor')
                                       ->where('numero_de_cedula_tutor', '!=', '');

        $totalTutors = $baseQueryForTutorStats->clone()
                                             ->distinct('numero_de_cedula_tutor')
                                             ->count('numero_de_cedula_tutor');
        
        // Tutores por Programa
        // Esta es una parte compleja debido a que 'programa' es un CSV.
        // La siguiente lógica intenta contar tutores únicos por cada programa individual.
        $tutorsByProgram = [];
        $allTutorProgramEntries = $baseQueryForTutorStats->clone()
                                    ->select('numero_de_cedula_tutor', 'programa')
                                    ->whereNotNull('programa')->where('programa', '!=', '')
                                    ->get();
        
        $programTutorCollector = [];
        foreach ($allTutorProgramEntries as $entry) {
            $tutorId = $entry->numero_de_cedula_tutor;
            $participantPrograms = array_map('trim', explode(',', $entry->programa));
            foreach ($participantPrograms as $prog) {
                if (empty($prog)) continue;
                // Si hay un filtro de programa global, solo nos interesa ese programa para el desglose.
                if ($selectedProgram && $prog != $selectedProgram) continue;

                if (!isset($programTutorCollector[$prog])) {
                    $programTutorCollector[$prog] = collect();
                }
                $programTutorCollector[$prog]->push($tutorId);
            }
        }
        foreach ($programTutorCollector as $prog => $tutorsList) {
            $tutorsByProgram[$prog] = $tutorsList->unique()->count();
        }
        arsort($tutorsByProgram); // Ordenar por cantidad de tutores, descendente.


        $tutorsBySector = $baseQueryForTutorStats->clone()
            ->whereNotNull('sector_economico_tutor')->where('sector_economico_tutor', '!=', '')
            ->groupBy('sector_economico_tutor')
            ->selectRaw('sector_economico_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'sector_economico_tutor')
            ->toArray();

        $tutorsByEducationLevel = $baseQueryForTutorStats->clone()
            ->whereNotNull('nivel_de_educacion_formal_adquirido_tutor')->where('nivel_de_educacion_formal_adquirido_tutor', '!=', '')
            ->groupBy('nivel_de_educacion_formal_adquirido_tutor')
            ->selectRaw('nivel_de_educacion_formal_adquirido_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'nivel_de_educacion_formal_adquirido_tutor')
            ->toArray();

        $tutorsByCommunity = $baseQueryForTutorStats->clone()
            ->whereNotNull('comunidad_tutor')->where('comunidad_tutor', '!=', '')
            ->groupBy('comunidad_tutor')
            ->selectRaw('comunidad_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'comunidad_tutor')
            ->toArray();

        // Total de participantes que cumplen con los filtros (no solo los que tienen tutor)
        $totalParticipantsInFilter = $query->clone()->count();
        $averageParticipantsPerTutor = $totalTutors > 0 ? round($totalParticipantsInFilter / $totalTutors, 1) : 0; // Promedio

        return view('tutores', compact(
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
            $query->where('programa', 'like', '%'. $selectedProgram .'%');
        }
        if ($selectedPlace) {
            $query->where('lugar_de_encuentro_del_programa', $selectedPlace);
        }

        $programOptions = $this->getProgramOptions();
        $placeOptions = $this->getPlaceOptions($selectedProgram); // Lugares filtrados por programa si aplica

        // Seleccionamos los campos necesarios para la vista de participantes por tutor
        $participantesData = $query->clone()->select([
            'numero_de_cedula_tutor', 'nombres_y_apellidos_tutor_principal',
            'tutor_principal', // Tipo de tutor (Padre, Madre, etc.)
            'programa', // Programas del participante
            'primer_nombre_p', 'segundo_nombre_p',
            'primer_apellido_p', 'segundo_apellido_p', 'grado_p'
        ])->where(function($q) { // Considerar solo registros con un tutor identificable
            $q->whereNotNull('numero_de_cedula_tutor')->where('numero_de_cedula_tutor', '!=', '')
              ->orWhere(function($q2) {
                  $q2->whereNotNull('nombres_y_apellidos_tutor_principal')->where('nombres_y_apellidos_tutor_principal', '!=', '');
              });
        })
        ->orderBy('nombres_y_apellidos_tutor_principal') // Ordenar por nombre del tutor
        ->orderBy('primer_apellido_p') // Luego por apellido del participante
        ->orderBy('primer_nombre_p')  // Luego por nombre del participante
        ->get();

        $tutors = [];
        foreach ($participantesData as $participante) {
            // Usar cédula como clave primaria para el tutor, si no, el nombre completo.
            $tutorKey = !empty($participante->numero_de_cedula_tutor) 
                        ? trim($participante->numero_de_cedula_tutor) 
                        : trim($participante->nombres_y_apellidos_tutor_principal);
            
            if (empty($tutorKey)) continue; // Si no hay identificador de tutor, saltar.

            if (!isset($tutors[$tutorKey])) {
                $tutors[$tutorKey] = [
                    'identificador_tutor' => $tutorKey,
                    'nombres_y_apellidos_tutor_principal' => $participante->nombres_y_apellidos_tutor_principal ?: 'N/A',
                    'tipos_tutor' => [], 
                    'programas_asociados_participantes' => [], // Programas de los participantes que supervisa
                    'participantes' => []
                ];
            }

            // Agregar tipo de tutor (Padre, Madre, etc.)
            $tipoTutor = trim($participante->tutor_principal ?: 'No especificado');
            if (!in_array($tipoTutor, $tutors[$tutorKey]['tipos_tutor'])) {
                $tutors[$tutorKey]['tipos_tutor'][] = $tipoTutor;
            }
            
            // Agregar programas en los que están los participantes de este tutor
            $programasDelParticipante = array_map('trim', explode(',', $participante->programa ?? ''));
            foreach($programasDelParticipante as $progP) {
                if(!empty($progP) && !in_array($progP, $tutors[$tutorKey]['programas_asociados_participantes'])) {
                    $tutors[$tutorKey]['programas_asociados_participantes'][] = $progP;
                }
            }

            // Construir nombre completo del participante
            $nombreParticipante = trim("{$participante->primer_nombre_p} {$participante->segundo_nombre_p} {$participante->primer_apellido_p} {$participante->segundo_apellido_p}");
            
            $tutors[$tutorKey]['participantes'][] = [
                'nombre_completo' => $nombreParticipante,
                'grado_p' => $participante->grado_p ?: 'N/A'
            ];
        }
        
        // Convertir arrays a strings para la vista
        foreach ($tutors as $key => $tutorData) {
            $tutors[$key]['tipos_tutor_str'] = implode(', ', array_unique($tutorData['tipos_tutor']));
            sort($tutors[$key]['programas_asociados_participantes']); // Ordenar programas
            $tutors[$key]['programas_asociados_str'] = implode(', ', array_unique($tutorData['programas_asociados_participantes']));
        }

        return view('tutores_participantes', compact(
            'tutors', 'programOptions', 'placeOptions', 'selectedProgram', 'selectedPlace'
        ));
    }

    /**
     * Endpoint AJAX para obtener lugares basados en el programa seleccionado para los filtros de tutores.
     * Este método es necesario si quieres que el filtro de lugares se actualice dinámicamente
     * en la vista de estadísticas de tutores (`/tutores`) cuando cambia el programa.
     */
    public function fetchPlacesForTutorFilters(Request $request)
    {
        $programFilter = $request->query('programa');
        Log::info('[EstadisticasTutorController.fetchPlacesForTutorFilters] Solicitud AJAX de lugares para filtros de tutor.', ['programa_solicitado' => $programFilter]);

        try {
            // Usamos el método privado getPlaceOptions, que ya maneja el filtro por programa.
            $places = $this->getPlaceOptions($programFilter); 
            
            Log::info('[EstadisticasTutorController.fetchPlacesForTutorFilters] Lugares encontrados:', ['count' => $places->count(), 'lugares' => $places->toArray()]);
            return response()->json($places);

        } catch (\Exception $e) {
            Log::error('[EstadisticasTutorController.fetchPlacesForTutorFilters] Excepción durante la búsqueda de lugares:', [
                'mensaje_error' => $e->getMessage(),
                'programa_solicitado' => $programFilter,
            ]);
            return response()->json(['error' => 'Error interno del servidor al cargar lugares.'], 500);
        }
    }
}
