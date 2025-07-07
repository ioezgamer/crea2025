<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstadisticasTutorController extends Controller
{
    /**
     * Get distinct program options from participants.
     */
    private function getProgramOptions() {
        return Participante::getDistinctProgramasOptions();
    }

    /**
     * Get distinct place options, optionally filtered by a program.
     */
    private function getPlaceOptions($selectedProgram = null) {
        $query = Participante::whereNotNull('lugar_de_encuentro_del_programa')
                             ->where('lugar_de_encuentro_del_programa', '!=', '');
        if ($selectedProgram) {
            // Filter places based on the selected program.
            $query->where('programa', 'like', '%' . $selectedProgram . '%');
        }
        return $query->distinct()
                      ->pluck('lugar_de_encuentro_del_programa')
                      ->filter()->sort()->values();
    }

    /**
     * Display the main statistics dashboard for tutors.
     */
    public function estadisticas(Request $request): View
    {
        $selectedProgram = $request->input('programa');
        $selectedPlace = $request->input('lugar');

        // Base query for participants, applying general filters.
        $query = Participante::query();

        if ($selectedProgram) {
            $query->where('programa', 'like', '%'. $selectedProgram .'%');
        }
        if ($selectedPlace) {
            $query->where('lugar_de_encuentro_del_programa', $selectedPlace);
        }

        // Base query for tutor-specific stats, ensuring tutor ID exists.
        $baseQueryForTutorStats = $query->clone()
                                       ->whereNotNull('numero_de_cedula_tutor')
                                       ->where('numero_de_cedula_tutor', '!=', '');

        // --- Key Metrics Calculation ---

        $totalTutors = $baseQueryForTutorStats->clone()
                                             ->distinct('numero_de_cedula_tutor')
                                             ->count('numero_de_cedula_tutor');

        // Tutors by Program (handling CSV 'programa' column)
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
                if (empty($prog) || ($selectedProgram && $prog != $selectedProgram)) continue;

                if (!isset($programTutorCollector[$prog])) {
                    $programTutorCollector[$prog] = collect();
                }
                $programTutorCollector[$prog]->push($tutorId);
            }
        }
        foreach ($programTutorCollector as $prog => $tutorsList) {
            $tutorsByProgram[$prog] = $tutorsList->unique()->count();
        }
        arsort($tutorsByProgram);

        // Tutors by Economic Sector
        $tutorsBySector = $baseQueryForTutorStats->clone()
            ->whereNotNull('sector_economico_tutor')->where('sector_economico_tutor', '!=', '')
            ->groupBy('sector_economico_tutor')
            ->selectRaw('sector_economico_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'sector_economico_tutor');

        // Tutors by Education Level
        $tutorsByEducationLevel = $baseQueryForTutorStats->clone()
            ->whereNotNull('nivel_de_educacion_formal_adquirido_tutor')->where('nivel_de_educacion_formal_adquirido_tutor', '!=', '')
            ->groupBy('nivel_de_educacion_formal_adquirido_tutor')
            ->selectRaw('nivel_de_educacion_formal_adquirido_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'nivel_de_educacion_formal_adquirido_tutor');

        // Tutors by Community
        $tutorsByCommunity = $baseQueryForTutorStats->clone()
            ->whereNotNull('comunidad_tutor')->where('comunidad_tutor', '!=', '')
            ->groupBy('comunidad_tutor')
            ->selectRaw('comunidad_tutor, count(distinct numero_de_cedula_tutor) as count')
            ->pluck('count', 'comunidad_tutor');

        // Average Participants per Tutor
        $totalParticipantsInFilter = $query->clone()->count();
        $averageParticipantsPerTutor = $totalTutors > 0 ? round($totalParticipantsInFilter / $totalTutors, 1) : 0;

        // Top 5 Tutors by number of participants
        $topTutors = $baseQueryForTutorStats->clone()
                    ->select(
                        'numero_de_cedula_tutor',
                        'nombres_y_apellidos_tutor_principal',
                        DB::raw('count(distinct participante_id) as participant_count')
                    )
                    ->where('nombres_y_apellidos_tutor_principal', '!=', 'No especificado') // <-- Aquí se excluyen
                    ->groupBy('numero_de_cedula_tutor', 'nombres_y_apellidos_tutor_principal')
                    ->orderBy('participant_count', 'desc')
                    ->limit(10)
                    ->get();


        // --- Prepare data for the view ---
        $programOptions = $this->getProgramOptions();
        $placeOptions = $this->getPlaceOptions($selectedProgram);

        return view('tutores', compact(
            'totalTutors', 'tutorsByProgram', 'tutorsBySector',
            'tutorsByEducationLevel', 'tutorsByCommunity', 'averageParticipantsPerTutor',
            'topTutors',
            'programOptions', 'placeOptions', 'selectedProgram', 'selectedPlace'
        ));
    }

    /**
     * Display a detailed list of tutors and their assigned participants.
     */


    /**
     * AJAX endpoint to fetch places based on the selected program for filters.
     */
    public function fetchPlacesForTutorFilters(Request $request)
    {
        $programFilter = $request->query('programa');
        try {
            $places = $this->getPlaceOptions($programFilter);
            return response()->json($places);
        } catch (\Exception $e) {
            Log::error('Exception during place lookup for tutor filters.', [
                'error_message' => $e->getMessage(),
                'program' => $programFilter,
            ]);
            return response()->json(['error' => 'Server error while loading places.'], 500);
        }
    }
}
