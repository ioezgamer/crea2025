<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\User; // Asegúrate de que el modelo User exista y esté en la ruta correcta
use Illuminate\Http\Request; 
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // Para consultas directas a la BD si son necesarias

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- Estadísticas de Participantes (código existente) ---
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

        // --- Nuevas Estadísticas de Usuarios ---
        $totalUsers = User::count();
        $approvedUsers = User::approved()->count(); // Usando el scope del modelo User
        $pendingUsers = User::pendingApproval()->count(); // Usando el scope del modelo User

        // Usuarios por rol
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
                            ->groupBy('role')
                            ->pluck('count', 'role');

        // Asignar conteos por roles específicos que manejes (ej. admin, editor, gestor, user)
        // Los roles deben coincidir con los definidos en tu sistema (ej. RoleController)
        $adminUsers = $usersByRole->get('admin', 0);
        $editorUsers = $usersByRole->get('editor', 0); 
        $gestorUsers = $usersByRole->get('gestor', 0);
        $standardUsers = $usersByRole->get('user', 0); // 'user' es un rol común

        // Total de Tutores (si los tutores son un tipo de User o se cuentan desde Participante)
        // Si los tutores son usuarios con un rol específico 'tutor':
        // $tutorsCount = $usersByRole->get('tutor', 0); 
        // O si se cuentan desde el modelo Participante (como en tu AppServiceProvider):
        $tutorsCountFromParticipants = Participante::distinct('numero_de_cedula_tutor')->count('numero_de_cedula_tutor');


        return view('dashboard', [
            // Datos de Participantes
            'totalParticipants' => $totalParticipants,
            'participantsByProgramData' => $programCounts,
            'participantsByPlaceData' => $participantsByPlace,
            'participantsByProgramForTable' => $programCounts,
            'participantsByPlaceForTable' => $participantsByPlace,

            // Nuevos Datos de Usuarios
            'totalUsers' => $totalUsers,
            'approvedUsers' => $approvedUsers,
            'pendingUsers' => $pendingUsers,
            'adminUsers' => $adminUsers,
            'editorUsers' => $editorUsers,
            'gestorUsers' => $gestorUsers,
            'standardUsers' => $standardUsers,
            'tutorsCount' => $tutorsCountFromParticipants, // Usando el conteo desde Participantes
        ]);
    }
}
