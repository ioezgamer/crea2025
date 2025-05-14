<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request; // No se usa directamente aquí, pero puede ser útil para futuras expansiones
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalParticipants = Participante::count();

        // --- Datos para el gráfico de Participantes por Programa ---
        // Si el campo 'programa' en la BD es un CSV (ej: "Exito Academico,Biblioteca")
        // necesitamos contar cada programa individualmente.
        $allProgramEntries = Participante::whereNotNull('programa')
                                ->where('programa', '!=', '')
                                ->pluck('programa'); // Obtiene todas las cadenas CSV del campo 'programa'

        $programCounts = [];
        foreach ($allProgramEntries as $programCsv) {
            $individualPrograms = explode(',', $programCsv); // Divide la cadena CSV en programas individuales
            foreach ($individualPrograms as $prog) {
                $trimmedProg = trim($prog); // Limpia espacios
                if (!empty($trimmedProg)) {
                    if (!isset($programCounts[$trimmedProg])) {
                        $programCounts[$trimmedProg] = 0;
                    }
                    $programCounts[$trimmedProg]++; // Incrementa el contador para ese programa
                }
            }
        }
        // Opcional: ordenar los programas por conteo (descendente) o alfabéticamente
        // arsort($programCounts); // Por conteo descendente
        // ksort($programCounts); // Alfabéticamente por nombre de programa

        // --- Datos para el gráfico de Participantes por Lugar de Encuentro ---
        // Este ya debería funcionar bien si 'lugar_de_encuentro_del_programa' es un valor único por registro.
        $participantsByPlace = Participante::groupBy('lugar_de_encuentro_del_programa')
            ->selectRaw('lugar_de_encuentro_del_programa, count(*) as count')
            ->whereNotNull('lugar_de_encuentro_del_programa') // Excluir nulos si no son relevantes
            ->where('lugar_de_encuentro_del_programa', '!=', '') // Excluir vacíos
            ->pluck('count', 'lugar_de_encuentro_del_programa')
            ->toArray();
        // ksort($participantsByPlace); // Opcional: ordenar alfabéticamente por lugar

        return view('dashboard', [
            'totalParticipants' => $totalParticipants,
            'participantsByProgramData' => $programCounts, // Datos para el gráfico de programas
            'participantsByPlaceData' => $participantsByPlace, // Datos para el gráfico de lugares
            // También puedes pasar los arrays originales si los usas en tablas además de los gráficos
            'participantsByProgramForTable' => $programCounts, // O la versión agrupada directa si la prefieres para la tabla
            'participantsByPlaceForTable' => $participantsByPlace,
        ]);
    }
}