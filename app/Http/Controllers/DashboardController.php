<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view with cached statistics.
     *
     * @return View
     */
    public function index(): View
    {
        $dashboardData = Cache::remember('dashboard_stats', now()->addHour(), function () {

            // ========================================================================
            // CAMBIO PRINCIPAL: Se define una consulta base para participantes válidos.
            // Un participante válido para las estadísticas está activo y tiene un programa.
            // ========================================================================
            $validParticipantsQuery = Participante::where('activo', true)
                ->whereNotNull('programa')
                ->where('programa', '!=', '');

            // --- Estadísticas de Participantes (basadas en la consulta unificada) ---

            // El conteo total ahora solo incluye participantes válidos.
            $totalParticipants = $validParticipantsQuery->count();

            // ========================================================================
            // LÓGICA MODIFICADA: Excluir subprograma 'Investigación' de 'Exito Academico'.
            // ========================================================================
            // Se obtienen ambos campos: programa principal y subprogramas.
            $participantsPrograms = $validParticipantsQuery->clone()->get(['programa', 'programas']);

            $programCounts = [];
            foreach ($participantsPrograms as $participant) {
                // Obtener arrays de programas principales y subprogramas, limpiando valores vacíos.
                $mainPrograms = array_filter(array_map('trim', explode(',', $participant->programa ?? '')));
                $subPrograms = array_filter(array_map('trim', explode(',', $participant->programas ?? '')));

                foreach ($mainPrograms as $mainProg) {
                    // Inicializar contador si no existe.
                    if (!isset($programCounts[$mainProg])) {
                        $programCounts[$mainProg] = 0;
                    }

                    // LÓGICA DE EXCLUSIÓN:
                    // Si el programa principal es "Exito Academico", solo se cuenta si el participante
                    // NO está también en el subprograma "Investigación".
                    if ($mainProg === 'Exito Academico') {
                        if (!in_array('Investigación', $subPrograms)) {
                            $programCounts[$mainProg]++;
                        }
                    } else {
                        // Para todos los demás programas, se cuenta normalmente.
                        $programCounts[$mainProg]++;
                    }
                }
            }
            ksort($programCounts);


            // La consulta por lugar también se basa en los participantes válidos.
            $participantsByPlace = $validParticipantsQuery->clone()
                ->groupBy('lugar_de_encuentro_del_programa')
                ->selectRaw('lugar_de_encuentro_del_programa, count(*) as count')
                ->whereNotNull('lugar_de_encuentro_del_programa')
                ->where('lugar_de_encuentro_del_programa', '!=', '')
                ->pluck('count', 'lugar_de_encuentro_del_programa')
                ->toArray();
            ksort($participantsByPlace);

            // Nuevos participantes este mes (también se filtra por activos).
            $newParticipantsThisMonth = Participante::where('activo', true)
                ->where('fecha_de_inscripcion', '>=', Carbon::now()->startOfMonth())->count();

            $newParticipantsByMonthFormatted = [];
            $currentYear = Carbon::now()->year;
            $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            foreach($months as $monthName) {
                $newParticipantsByMonthFormatted[$monthName] = 0;
            }

            $newParticipantsByMonthData = Participante::where('activo', true)
                ->select(DB::raw("MONTH(fecha_de_inscripcion) as month"), DB::raw('count(*) as count'))
                ->whereYear('fecha_de_inscripcion', $currentYear)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            foreach ($newParticipantsByMonthData as $entry) {
                $key = $months[$entry->month - 1];
                $newParticipantsByMonthFormatted[$key] = $entry->count;
            }

            // --- Estadísticas de Usuarios (sin cambios) ---
            $totalUsers = User::count();
            $approvedUsers = User::approved()->count();
            $pendingUsers = User::pendingApproval()->count();

            $usersByRole = Role::withCount('users')->pluck('users_count', 'name');
            $adminUsers = $usersByRole->get('Administrador', 0);
            $coordinadorUsers = $usersByRole->get('Coordinador', 0);
            $facilitadorUsers = $usersByRole->get('Facilitador', 0);
            $invitadoUsers = $usersByRole->get('Invitado', 0);
            $tutorsCountFromParticipants = $validParticipantsQuery->clone()->distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');

            return [
                'totalParticipants' => $totalParticipants, // Este número ahora será consistente
                'participantsByProgramData' => $programCounts,
                'participantsByPlaceData' => $participantsByPlace,
                'participantsByProgramForTable' => $programCounts,
                'participantsByPlaceForTable' => $participantsByPlace,
                'newParticipantsThisMonth' => $newParticipantsThisMonth,
                'newParticipantsByMonth' => $newParticipantsByMonthFormatted,
                'totalUsers' => $totalUsers,
                'approvedUsers' => $approvedUsers,
                'pendingUsers' => $pendingUsers,
                'adminUsers' => $adminUsers,
                'coordinadorUsers' => $coordinadorUsers,
                'facilitadorUsers' => $facilitadorUsers,
                'invitadoUsers' => $invitadoUsers,
                'tutorsCount' => $tutorsCountFromParticipants,
            ];
        });

        return view('dashboard', $dashboardData);
    }
}
