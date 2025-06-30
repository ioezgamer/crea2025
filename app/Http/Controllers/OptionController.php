<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OptionController extends Controller
{
    /**
     * Get a list of "lugares de encuentro" based on the selected "programa".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLugares(Request $request)
    {
        $request->validate(['programa' => 'nullable|string']);
        $programa = $request->query('programa');

        if (!$programa) {
            return response()->json([]);
        }

        try {
            // Reutilizamos la lógica del modelo si existe o la definimos aquí.
            // Esta consulta busca lugares únicos donde el programa está presente.
            $lugares = Participante::where('programa', 'LIKE', '%' . $programa . '%')
                                    ->whereNotNull('lugar_de_encuentro_del_programa')
                                    ->where('lugar_de_encuentro_del_programa', '!=', '')
                                    ->distinct()
                                    ->orderBy('lugar_de_encuentro_del_programa')
                                    ->pluck('lugar_de_encuentro_del_programa');

            return response()->json($lugares);

        } catch (\Exception $e) {
            Log::error('Error fetching lugares: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los lugares.'], 500);
        }
    }

    /**
     * Get a list of "grados" based on the selected "programa" and "lugar".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGrados(Request $request)
    {
        $request->validate([
            'programa' => 'nullable|string',
            'lugar' => 'nullable|string',
        ]);

        $programa = $request->query('programa');
        $lugar = $request->query('lugar');

        // Solo procedemos si al menos el programa está presente.
        if (!$programa) {
            return response()->json([]);
        }

        try {
            $query = Participante::query()
                ->where('programa', 'LIKE', '%' . $programa . '%')
                ->whereNotNull('grado_p')
                ->where('grado_p', '!=', '');

            if ($lugar) {
                $query->where('lugar_de_encuentro_del_programa', $lugar);
            }

            $grados = $query->distinct()->orderBy('grado_p')->pluck('grado_p');

            return response()->json($grados);

        } catch (\Exception $e) {
            Log::error('Error fetching grados: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los grados.'], 500);
        }
    }
}
