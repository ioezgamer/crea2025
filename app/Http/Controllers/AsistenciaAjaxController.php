<?php

namespace App\Http\Controllers;

use App\Services\AsistenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AsistenciaAjaxController extends Controller
{
    protected AsistenciaService $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }

    public function opcionesLugares(Request $request)
    {
        $programa = $request->query('programa');
        if (!$programa) {
            return response()->json([], 400);
        }

        $lugares = $this->asistenciaService->getLugaresEncuentro($programa);
        return response()->json($lugares);
    }

    /**
     * Obtiene los grados filtrados.
     * CORREGIDO: Ahora maneja el caso donde 'lugar' es opcional.
     */
    public function opcionesGrados(Request $request)
    {
        $programa = $request->query('programa');
        // El lugar es ahora opcional. Si no se proporciona, se buscarán los grados para todo el programa.
        $lugar = $request->query('lugar_de_encuentro_del_programa');

        // El programa sigue siendo requerido.
        if (!$programa) {
            return response()->json(['error' => 'El parámetro programa es requerido.'], 400);
        }

        // Se asume que el servicio puede manejar un valor null para $lugar.
        $grados = $this->asistenciaService->getGrados($programa, $lugar);
        return response()->json($grados);
    }

    public function participantes(Request $request)
    {
        $filters = $request->only([
            'programa',
            'lugar_de_encuentro_del_programa',
            'grado_p',
            'fecha',
            'tipo_asistencia'
        ]);

        if (!array_filter($filters)) {
            return response()->json(['error' => 'Faltan filtros necesarios.'], 422);
        }

        try {
            $fechaCarbon = Carbon::parse($filters['fecha']);
            $diasSemana = $this->asistenciaService->getDiasParaAsistencia($fechaCarbon, $filters['tipo_asistencia']);

            [$participantes, $asistencias] = $this->asistenciaService->cargarParticipantesYAsistencias(
                $filters['programa'],
                $filters['lugar_de_encuentro_del_programa'],
                $filters['grado_p'],
                $fechaCarbon,
                $filters['tipo_asistencia'],
                $diasSemana
            );

            $html = view('asistencia.partials.tabla_asistencia', compact(
                'participantes',
                'asistencias',
                'diasSemana'
            ))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => 'Error al procesar participantes.'], 500);
        }
    }

    public function storeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'fecha_asistencia' => 'required|date_format:Y-m-d',
            'estado' => 'required|in:Presente,Ausente,Justificado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $attributes = [
                'participante_id' => $request->input('participante_id'),
                'fecha_asistencia' => $request->input('fecha_asistencia'),
            ];

            $values = [
                'estado' => $request->input('estado'),
                'updated_at' => now(),
            ];

            $existing = DB::table('asistencias')->where($attributes)->first();

            if ($existing) {
                DB::table('asistencias')->where('id', $existing->id)->update($values);
            } else {
                DB::table('asistencias')->insert(array_merge(
                    $attributes,
                    $values,
                    ['created_at' => now()]
                ));
            }

            return response()->json(['success' => true, 'message' => 'Asistencia actualizada.']);
        } catch (\Exception $e) {
            Log::error('Error al guardar asistencia individual: ' . $e->getMessage(), $request->all());

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

	/**
	 * @return AsistenciaService
	 */
	public function getAsistenciaService(): \App\Services\AsistenciaService {
		return $this->asistenciaService;
	}
}
