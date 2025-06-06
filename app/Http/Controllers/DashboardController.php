<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\User; // Asegúrate de que el modelo User exista y esté en la ruta correcta
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // Para consultas directas a la BD si son necesarias
use Carbon\Carbon; // Import Carbon for date manipulation

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- Estadísticas de Participantes ---
        $totalParticipants = Participante::count();

        $allProgramEntries = Participante::whereNotNull('programa')
                                     ->where('programa', '!=', '')
                                     ->pluck('programa');

        $programCounts = [];
        foreach ($allProgramEntries as $programCsv) {
            $individualPrograms = explode(',', $programCsv);
            foreach ($individualPrograms as $prog) {
                $trimmedProg = trim($prog);
                if (!empty($trimmedProg)) {
                    if (!isset($programCounts[$trimmedProg])) {
                        $programCounts[$trimmedProg] = 0;
                    }
                    $programCounts[$trimmedProg]++;
                }
            }
        }
        ksort($programCounts);

        $participantsByPlace = Participante::groupBy('lugar_de_encuentro_del_programa')
            ->selectRaw('lugar_de_encuentro_del_programa, count(*) as count')
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->where('lugar_de_encuentro_del_programa', '!=', '')
            ->pluck('count', 'lugar_de_encuentro_del_programa')
            ->toArray();
        ksort($participantsByPlace);

        // --- Nuevas Estadísticas de Inscripción de Participantes ---
        $currentMonthStart = Carbon::now()->startOfMonth();
        $newParticipantsThisMonth = Participante::where('fecha_de_inscripcion', '>=', $currentMonthStart)->count();

        $newParticipantsByMonthData = Participante::select(
                DB::raw("DATE_FORMAT(fecha_de_inscripcion, '%Y-%m') as month_year_key"), // Usar un alias diferente para la clave original
                DB::raw("DATE_FORMAT(fecha_de_inscripcion, '%b %Y') as month_year_label"), // Etiqueta para el gráfico
                DB::raw('count(*) as count')
            )
            ->where('fecha_de_inscripcion', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month_year_key', 'month_year_label') // Agrupar por ambos para mantener la etiqueta
            ->orderBy('month_year_key', 'asc') // Ordenar por la clave YYYY-MM
            ->get();

        $newParticipantsByMonthFormatted = [];
        // Crear un array con los últimos 12 meses como claves (formato 'M Y') y 0 como valor
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            // Usar Carbon para formatear el mes y año, asegurando consistencia y localización si es necesario
            $newParticipantsByMonthFormatted[$date->locale(config('app.locale', 'es'))->isoFormat('MMM YYYY')] = 0;
        }

        // Llenar con los datos reales de la base de datos
        foreach ($newParticipantsByMonthData as $entry) {
            // Usar la etiqueta formateada directamente desde la consulta
            $newParticipantsByMonthFormatted[$entry->month_year_label] = $entry->count;
        }


        // --- Estadísticas de Usuarios ---
        $totalUsers = User::count();

        // AJUSTE: Usar los scopes del modelo User para consistencia, basados en 'approved_at'
        $approvedUsers = User::approved()->count();
        $pendingUsers = User::pendingApproval()->count();


        $usersByRole = User::select('role', DB::raw('count(*) as count'))
                            ->groupBy('role')
                            ->pluck('count', 'role');

        $adminUsers = $usersByRole->get('admin', 0);
        $editorUsers = $usersByRole->get('editor', 0);
        $gestorUsers = $usersByRole->get('gestor', 0);
        $standardUsers = $usersByRole->get('user', 0); // Asumiendo 'user' es el rol estándar

        $tutorsCountFromParticipants = Participante::distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');


        return view('dashboard', [
            'totalParticipants' => $totalParticipants,
            'participantsByProgramData' => $programCounts,
            'participantsByPlaceData' => $participantsByPlace,
            'participantsByProgramForTable' => $programCounts, // Considera si necesitas datos diferentes para tabla y gráfico
            'participantsByPlaceForTable' => $participantsByPlace, // Considera si necesitas datos diferentes para tabla y gráfico
            'newParticipantsThisMonth' => $newParticipantsThisMonth,
            'newParticipantsByMonth' => $newParticipantsByMonthFormatted,

            'totalUsers' => $totalUsers,
            'approvedUsers' => $approvedUsers,
            'pendingUsers' => $pendingUsers,
            'adminUsers' => $adminUsers,
            'editorUsers' => $editorUsers,
            'gestorUsers' => $gestorUsers,
            'standardUsers' => $standardUsers,
            'tutorsCount' => $tutorsCountFromParticipants, // Este parece ser un conteo de tutores basado en la tabla Participantes
        ]);
    }
}
