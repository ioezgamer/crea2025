<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\User;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Validar el mes de entrada, si no, usar el mes actual.
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Exception $e) {
            $selectedMonth = Carbon::now()->format('Y-m');
            $monthDate = Carbon::now()->startOfMonth();
        }

        $cacheDuration = now()->addHour();
        // La clave de caché ahora incluye el mes seleccionado para evitar datos incorrectos.
        $cacheKey = 'dashboard_stats_v4_' . $selectedMonth;

        $dashboardData = Cache::remember($cacheKey, $cacheDuration, function () use ($monthDate) {

            // Consulta base para participantes válidos (activos y con programa).
            $validParticipantsQuery = Participante::where('activo', true)
                ->whereNotNull('programa')
                ->where('programa', '!=', '');

            // --- Estadísticas Generales de Participantes ---
            $totalParticipants = $validParticipantsQuery->count();

            $participantsPrograms = $validParticipantsQuery->clone()->get(['programa', 'programas']);
            $programCounts = [];
            foreach ($participantsPrograms as $participant) {
                $mainPrograms = array_filter(array_map('trim', explode(',', $participant->programa ?? '')));
                $subPrograms = array_filter(array_map('trim', explode(',', $participant->programas ?? '')));
                foreach ($mainPrograms as $mainProg) {
                    if (!isset($programCounts[$mainProg])) $programCounts[$mainProg] = 0;
                    if ($mainProg === 'Exito Academico' && in_array('Investigación', $subPrograms)) continue;
                    $programCounts[$mainProg]++;
                }
            }
            ksort($programCounts);

            $participantsByPlace = $validParticipantsQuery->clone()
                ->groupBy('lugar_de_encuentro_del_programa')
                ->selectRaw('lugar_de_encuentro_del_programa, count(*) as count')
                ->whereNotNull('lugar_de_encuentro_del_programa')->where('lugar_de_encuentro_del_programa', '!=', '')
                ->pluck('count', 'lugar_de_encuentro_del_programa')->toArray();
            ksort($participantsByPlace);

            $newParticipantsThisMonth = Participante::where('activo', true)
                ->where('fecha_de_inscripcion', '>=', Carbon::now()->startOfMonth())->count();

            $newParticipantsByMonthFormatted = [];
            $currentYear = Carbon::now()->year;
            $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            foreach ($months as $monthName) $newParticipantsByMonthFormatted[$monthName] = 0;

            $newParticipantsByMonthData = Participante::where('activo', true)
                ->select(DB::raw("MONTH(fecha_de_inscripcion) as month"), DB::raw('count(*) as count'))
                ->whereYear('fecha_de_inscripcion', $currentYear)
                ->groupBy('month')->orderBy('month', 'asc')->get();
            foreach ($newParticipantsByMonthData as $entry) {
                $newParticipantsByMonthFormatted[$months[$entry->month - 1]] = $entry->count;
            }

            // --- Estadísticas de Usuarios ---
            $totalUsers = User::count();
            $approvedUsers = User::approved()->count();
            $pendingUsers = User::pendingApproval()->count();
            $usersByRole = Role::withCount('users')->pluck('users_count', 'name');
            $tutorsCountFromParticipants = $validParticipantsQuery->clone()->distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');

            // --- Estadísticas de Asistencia (Ahora con Promedios) ---
            $startOfMonth = $monthDate->copy()->startOfMonth();
            $endOfMonth = $monthDate->copy()->endOfMonth();

            $baseAttendanceQuery = DB::table('asistencias')
                ->join('participantes', 'asistencias.participante_id', '=', 'participantes.participante_id')
                ->where('participantes.activo', true)
                ->whereBetween('asistencias.fecha_asistencia', [$startOfMonth, $endOfMonth])
                ->where('asistencias.estado', 'Presente');

            // Top 10 Participantes (sigue mostrando el total de días)
            $topParticipants = $baseAttendanceQuery->clone()
                ->select('participantes.primer_nombre_p', 'participantes.primer_apellido_p', DB::raw('count(asistencias.id) as total_asistencias'))
                ->groupBy('participantes.participante_id', 'participantes.primer_nombre_p', 'participantes.primer_apellido_p')
                ->orderBy('total_asistencias', 'desc')->limit(10)->get();

            // Top 5 Programas (con promedio)
            $topPrograms = $baseAttendanceQuery->clone()
                ->select('participantes.programa',
                    DB::raw('COUNT(asistencias.id) as total_presente'),
                    DB::raw('COUNT(DISTINCT asistencias.participante_id) as total_participantes_con_asistencia')
                )
                ->whereNotNull('participantes.programa')->where('participantes.programa', '!=', '')
                ->groupBy('participantes.programa')->orderBy('total_presente', 'desc')->limit(5)->get()
                ->map(function ($item) {
                    $item->promedio_asistencias = $item->total_participantes_con_asistencia > 0
                        ? round($item->total_presente / $item->total_participantes_con_asistencia, 1) : 0;
                    return $item;
                });

            // Top 5 Grados (con promedio)
            $topGrades = $baseAttendanceQuery->clone()
                ->select('participantes.grado_p',
                    DB::raw('COUNT(asistencias.id) as total_presente'),
                    DB::raw('COUNT(DISTINCT asistencias.participante_id) as total_participantes_con_asistencia')
                )
                ->whereNotNull('participantes.grado_p')->where('participantes.grado_p', '!=', '')
                ->groupBy('participantes.grado_p')->orderBy('total_presente', 'desc')->limit(5)->get()
                ->map(function ($item) {
                    $item->promedio_asistencias = $item->total_participantes_con_asistencia > 0
                        ? round($item->total_presente / $item->total_participantes_con_asistencia, 1) : 0;
                    return $item;
                });

            // Top 5 Lugares de Encuentro (con promedio)
            $topPlaces = $baseAttendanceQuery->clone()
                ->select('participantes.lugar_de_encuentro_del_programa',
                    DB::raw('COUNT(asistencias.id) as total_presente'),
                    DB::raw('COUNT(DISTINCT asistencias.participante_id) as total_participantes_con_asistencia')
                )
                ->whereNotNull('participantes.lugar_de_encuentro_del_programa')->where('participantes.lugar_de_encuentro_del_programa', '!=', '')
                ->groupBy('participantes.lugar_de_encuentro_del_programa')->orderBy('total_presente', 'desc')->limit(5)->get()
                ->map(function ($item) {
                    $item->promedio_asistencias = $item->total_participantes_con_asistencia > 0
                        ? round($item->total_presente / $item->total_participantes_con_asistencia, 1) : 0;
                    return $item;
                });

            return [
                'totalParticipants' => $totalParticipants,
                'participantsByProgramData' => $programCounts, 'participantsByPlaceData' => $participantsByPlace,
                'participantsByProgramForTable' => $programCounts, 'participantsByPlaceForTable' => $participantsByPlace,
                'newParticipantsThisMonth' => $newParticipantsThisMonth, 'newParticipantsByMonth' => $newParticipantsByMonthFormatted,
                'totalUsers' => $totalUsers, 'approvedUsers' => $approvedUsers, 'pendingUsers' => $pendingUsers,
                'adminUsers' => $usersByRole->get('Administrador', 0), 'coordinadorUsers' => $usersByRole->get('Coordinador', 0),
                'facilitadorUsers' => $usersByRole->get('Facilitador', 0), 'invitadoUsers' => $usersByRole->get('Invitado', 0),
                'tutorsCount' => $tutorsCountFromParticipants,
                'topParticipants' => $topParticipants, 'topPrograms' => $topPrograms,
                'topGrades' => $topGrades, 'topPlaces' => $topPlaces,
                'monthName' => ucfirst($monthDate->translatedFormat('F Y')),
            ];
        });

        $availableMonths = Cache::remember('available_attendance_months_v2', now()->addDay(), function () {
            return DB::table('asistencias')
                ->select(DB::raw("DISTINCT DATE_FORMAT(fecha_asistencia, '%Y-%m') as month_value"))
                ->orderBy('month_value', 'asc')->get()
                ->map(function ($item) {
                    $date = Carbon::createFromFormat('Y-m', $item->month_value);
                    return (object)['value' => $item->month_value, 'name' => ucfirst($date->translatedFormat('F Y'))];
                });
        });

        $viewData = array_merge($dashboardData, [
            'availableMonths' => $availableMonths,
            'selectedMonth' => $selectedMonth,
        ]);

        return view('dashboard', $viewData);
    }
}
