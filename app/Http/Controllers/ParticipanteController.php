<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use App\Http\Requests\StoreParticipanteRequest;
use App\Http\Requests\UpdateParticipanteRequest;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use App\Exports\ParticipantesExport;
use App\Imports\ParticipantesImport;
use Maatwebsite\Excel\Facades\Excel;

class ParticipanteController extends Controller
{
   public function index(Request $request)
    {
        $search_name = $request->input('search_name');
        $search_programa = $request->input('search_programa');
        $search_lugar = $request->input('search_lugar');
        $search_grado = $request->input('search_grado');
        $grado_param_url = $request->input('grado');

        $query = Participante::query()
            ->filterByName($search_name)
            ->filterByPrograma($search_programa)
            ->filterByLugar($search_lugar);

        if ($search_grado) {
            $query->filterByGrado($search_grado);
        } elseif ($grado_param_url) {
            $query->filterByGrado(urldecode($grado_param_url));
        }

        $query->orderBy('grado_p', 'asc')->orderBy('primer_apellido_p')->orderBy('primer_nombre_p');

        $participantes = $query->paginate($request->input('per_page', 20));
        $programOptions = Participante::getDistinctProgramasOptions();
        $gradoOptionsQuery = Participante::select('grado_p')->distinct()
                            ->whereNotNull('grado_p')->where('grado_p', '!=', '');
        if ($search_programa) {
            $gradoOptionsQuery->where('programa', 'like', '%' . $search_programa . '%');
        }
        if ($search_programa && $search_lugar) {
            $gradoOptionsQuery->where('lugar_de_encuentro_del_programa', $search_lugar);
        }
        $gradoOptions = $gradoOptionsQuery->orderBy('grado_p')->pluck('grado_p')->toArray();

        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions)
                ->with('gradoOptions', $gradoOptions)
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('search_grado', $search_grado)
                ->with('grado', $grado_param_url);
    }

    public function indexByGrade(Request $request, $gradoParam)
    {
        // Esta función es similar a index, asegúrate de que también pase los datos necesarios para los filtros si se usan
        $decodedGrado = urldecode($gradoParam);
        $search_name = $request->input('search_name');
        $search_programa = $request->input('search_programa');
        $search_lugar = $request->input('search_lugar');
        // Considera si necesitas $search_grado aquí también o si $gradoParam es el único filtro de grado

        $query = Participante::query()
            ->filterByGrado($decodedGrado) // Usar el grado de la URL
            ->filterByName($search_name)
            ->filterByPrograma($search_programa)
            ->filterByLugar($search_lugar);

        $query->orderBy('primer_apellido_p')->orderBy('primer_nombre_p');
        $participantes = $query->paginate($request->input('per_page', 15));

        $programOptions = Participante::getDistinctProgramasOptions();
        // Obtener opciones de grado para la vista, similar a como se hace en index()
        $gradoOptionsQuery = Participante::select('grado_p')->distinct()
                            ->whereNotNull('grado_p')->where('grado_p', '!=', '');
        if ($search_programa) {
            $gradoOptionsQuery->where('programa', 'like', '%' . $search_programa . '%');
        }
        if ($search_programa && $search_lugar) {
            $gradoOptionsQuery->where('lugar_de_encuentro_del_programa', $search_lugar);
        }
        // Para la página específica de grado, tal vez quieras preseleccionar el grado del filtro
        // o simplemente mostrar todos los grados disponibles para esos filtros de programa/lugar.
        $gradoOptions = $gradoOptionsQuery->orderBy('grado_p')->pluck('grado_p')->toArray();


        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions)
                ->with('gradoOptions', $gradoOptions) // Pasar opciones de grado
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('grado', $gradoParam) // Mantener el parámetro de grado de la URL
                ->with('search_grado', $decodedGrado); // Para preseleccionar el grado en el filtro
    }

    public function getLugaresByPrograma(Request $request)
    {
        $programaFilter = $request->query('programa');
        Log::info('[getLugaresByPrograma] Iniciando búsqueda de lugares.', ['programa_solicitado' => $programaFilter]);

        if (empty($programaFilter)) {
            Log::warning('[getLugaresByPrograma] No se proporcionó filtro de programa. Devolviendo array vacío.');
            return response()->json([]);
        }
        try {
            $participantesConPosiblePrograma = Participante::where('programa', 'LIKE', '%' . $programaFilter . '%')
                                                ->whereNotNull('lugar_de_encuentro_del_programa')
                                                ->where('lugar_de_encuentro_del_programa', '!=', '')
                                                ->select('programa', 'lugar_de_encuentro_del_programa')
                                                ->get();
            $lugaresFiltrados = collect();
            foreach ($participantesConPosiblePrograma as $participante) {
                $programasDelParticipante = array_map('trim', explode(',', $participante->programa ?? ''));
                if (in_array($programaFilter, $programasDelParticipante)) {
                    $lugaresFiltrados->push($participante->lugar_de_encuentro_del_programa);
                }
            }
            $lugares = $lugaresFiltrados->unique()->filter()->sort()->values();
            Log::info('[getLugaresByPrograma] Lugares finales procesados:', ['count' => $lugares->count(), 'lugares' => $lugares->toArray()]);
            return response()->json($lugares);
        } catch (\Exception $e) {
            Log::error('[getLugaresByPrograma] Excepción durante la búsqueda de lugares:', [
                'mensaje_error' => $e->getMessage(), 'programa_solicitado' => $programaFilter,
                'archivo_error' => $e->getFile(), 'linea_error' => $e->getLine(),
            ]);
            return response()->json(['error' => 'Error interno del servidor al cargar lugares.'], 500);
        }
    }

    public function create()
    {
        $comunidades = Participante::distinct()->pluck('comunidad_tutor')->filter()->sort()->values();
        $sector_economico = Participante::distinct()->pluck('sector_economico_tutor')->filter()->sort()->values();
        $nivel_educacion = Participante::distinct()->pluck('nivel_de_educacion_formal_adquirido_tutor')->filter()->sort()->values();
        $tipos_tutor_db = Participante::distinct()->pluck('tutor_principal')->filter()->sort()->values();
        $tipos_tutor_estaticos = ['Padre', 'Madre', 'Abuelo/a', 'Tío/a', 'Otro'];
        $tipos_tutor = $tipos_tutor_db->merge($tipos_tutor_estaticos)->unique()->sort()->values();
        $tiposParticipanteDB = Participante::distinct()
                                ->whereNotNull('participante')
                                ->where('participante', '!=', '')
                                ->pluck('participante')
                                ->filter()->sort()->values();
        $tiposParticipanteEstaticos = ['Preescolar (o menos)', 'Primaria', 'Secundaria', 'Adulto'];
        $tiposParticipante = collect($tiposParticipanteEstaticos)->merge($tiposParticipanteDB)->unique()->sort()->values();
        $programaOptionsList = ['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'];
        $subProgramaOptionsList = ['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM'];
        $diasOptionsList = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('participante.create', compact(
            'comunidades', 'tipos_tutor', 'sector_economico', 'nivel_educacion',
            'programaOptionsList', 'subProgramaOptionsList', 'diasOptionsList',
            'tiposParticipante'
        ));
    }

    public function store(StoreParticipanteRequest $request)
    {
        $validated = $request->validated();
        // Convertir arrays a CSV antes de guardar
        if (isset($validated['dias_de_asistencia_al_programa']) && is_array($validated['dias_de_asistencia_al_programa'])) {
            $validated['dias_de_asistencia_al_programa'] = implode(',', $validated['dias_de_asistencia_al_programa']);
        }
        if (isset($validated['programas']) && is_array($validated['programas'])) {
            $validated['programas'] = implode(',', $validated['programas']);
        }
        if (isset($validated['programa']) && is_array($validated['programa'])) {
            $validated['programa'] = implode(',', $validated['programa']);
        }
        Participante::create($validated);
        return redirect()->route('participante.index')
                         ->with('success', 'Participante creado exitosamente.');
    }

    public function show(Participante $participante)
    {
        $participante->dias_de_asistencia_al_programa_array = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
        $participante->programas_array = !empty($participante->programas) ? explode(',', $participante->programas) : [];
        $participante->programa_array = !empty($participante->programa) ? explode(',', $participante->programa) : [];
        return view('participante.show', compact('participante'));
    }

    public function exportPdf($id)
    {
        try {
            \Carbon\Carbon::setLocale('es');
            $participante = Participante::findOrFail($id);
            $participante->dias_de_asistencia_al_programa_array = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
            $participante->programas_array = !empty($participante->programas) ? explode(',', $participante->programas) : [];
            $participante->programa_array = !empty($participante->programa) ? explode(',', $participante->programa) : [];

            $html = view('participante.pdf', compact('participante'))->render();
            $mpdf = new Mpdf(['format' => 'Letter', 'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15]);
            $mpdf->WriteHTML($html);
            $nombreArchivo = "Ficha_Participante_{$participante->primer_nombre_p}_{$participante->primer_apellido_p}.pdf";
            return $mpdf->Output($nombreArchivo, 'D'); // 'D' para descargar
        } catch (\Exception $e) {
            Log::error("Error al generar PDF para participante ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo generar el PDF del participante: ' . $e->getMessage());
        }
    }

    public function edit(Participante $participante)
    {
        $comunidades = Participante::distinct()->pluck('comunidad_tutor')->filter()->sort()->values();
        $sector_economico = Participante::distinct()->pluck('sector_economico_tutor')->filter()->sort()->values();
        $nivel_educacion = Participante::distinct()->pluck('nivel_de_educacion_formal_adquirido_tutor')->filter()->sort()->values();
        $tipos_tutor_db = Participante::distinct()->pluck('tutor_principal')->filter()->sort()->values();
        $tipos_tutor_estaticos = ['Padre', 'Madre', 'Abuelo/a', 'Tío/a', 'Otro'];
        $tipos_tutor = $tipos_tutor_db->merge($tipos_tutor_estaticos)->unique()->sort()->values();
        $tiposParticipanteDB = Participante::distinct()
                                ->whereNotNull('participante')
                                ->where('participante', '!=', '')
                                ->pluck('participante')
                                ->filter()->sort()->values();
        $tiposParticipanteEstaticos = ['Preescolar (o menos)', 'Primaria', 'Secundaria', 'Adulto'];
        $tiposParticipante = collect($tiposParticipanteEstaticos)->merge($tiposParticipanteDB)->unique()->sort()->values();

        $participante->dias_de_asistencia_al_programa = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
        $participante->programas = !empty($participante->programas) ? explode(',', $participante->programas) : [];
        $participante->programa = !empty($participante->programa) ? explode(',', $participante->programa) : [];
        $programaOptionsList = ['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'];
        $subProgramaOptionsList = ['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM'];
        $diasOptionsList = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('participante.edit', compact(
            'participante', 'comunidades', 'tipos_tutor', 'sector_economico', 'nivel_educacion',
            'programaOptionsList', 'subProgramaOptionsList', 'diasOptionsList', 'tiposParticipante'
        ));
    }

    public function update(UpdateParticipanteRequest $request, Participante $participante)
    {
        $validated = $request->validated();
        if (isset($validated['dias_de_asistencia_al_programa']) && is_array($validated['dias_de_asistencia_al_programa'])) {
            $validated['dias_de_asistencia_al_programa'] = implode(',', $validated['dias_de_asistencia_al_programa']);
        }
        if (isset($validated['programas']) && is_array($validated['programas'])) {
            $validated['programas'] = implode(',', $validated['programas']);
        }
        if (isset($validated['programa']) && is_array($validated['programa'])) {
            $validated['programa'] = implode(',', $validated['programa']);
        }
        $participante->update($validated);
        return redirect()->route('participante.index')->with('success', 'Participante actualizado correctamente.');
    }

    public function destroy(Participante $participante)
    {
        try {
            $participante->delete();
            return redirect()->route('participante.index')->with('success', 'Participante eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar participante ID {$participante->participante_id}: " . $e->getMessage());
            return redirect()->route('participante.index')->with('error', 'No se pudo eliminar el participante: ' . $e->getMessage());
        }
    }

    public function toggleActivo(Request $request)
    {
        // Esta función ya devuelve JSON, lo cual es correcto para AJAX.
        // Los mensajes de éxito/error se manejan en el JS que llama a esta ruta.
        $validated = $request->validate([
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'activo' => 'required|boolean'
        ]);
        try {
            Log::info('Iniciando toggleActivo', ['input' => $validated]);
            $participanteToToggle = Participante::findOrFail($validated['participante_id']);
            $participanteToToggle->activo = $validated['activo'];
            $updated = $participanteToToggle->save();
            Log::info('Resultado de update', ['participante_id' => $validated['participante_id'], 'activo' => $validated['activo'], 'updated_status' => $updated]);
            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
            } else {
                Log::warning('No se actualizó ningún registro o el estado no cambió', ['participante_id' => $validated['participante_id']]);
                return response()->json(['success' => false, 'message' => 'No se pudo actualizar el participante o el estado ya era el mismo.'], 422);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Participante no encontrado en toggleActivo', ['error' => $e->getMessage(), 'input' => $validated]);
            return response()->json(['success' => false, 'message' => 'Participante no encontrado.'], 404);
        } catch (\Exception $e) {
            Log::error('Error en toggleActivo', ['error' => $e->getMessage(), 'input' => $validated]);
            return response()->json(['success' => false, 'message' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }

    public function exportParticipantes(Request $request)
    {
        try {
            $filters = $request->only(['search_name', 'search_programa', 'search_lugar', 'grado']);
            $timestamp = now()->format('Y-m-d_H-i-s');
            // No hay una redirección directa aquí para un mensaje flash, pero si Excel::download fallara,
            // podría lanzar una excepción que sería capturada.
            return Excel::download(new ParticipantesExport($filters), "participantes_{$timestamp}.xlsx");
        } catch (\Exception $e) {
            Log::error("Error al exportar participantes: " . $e->getMessage());
            // Es difícil agregar un `with()` a una descarga directa.
            // Una mejor UX sería mostrar un error en la página anterior si la preparación falla.
            // O, si la descarga inicia pero luego falla, el navegador podría mostrar el error.
            // Para este caso, no podemos añadir un flash message directamente a la respuesta de descarga.
            // Podríamos redirigir con error SI la generación del archivo falla ANTES de la descarga.
            return redirect()->back()->with('error', 'Error al generar el archivo de exportación: ' . $e->getMessage());
        }
    }

    public function showImportForm()
    {
        return view('participante.import_form');
    }

    public function importParticipantes(Request $request)
    {
        $request->validate([
            'participantes_file' => 'required|mimes:xlsx,xls,csv|max:20480'
        ]);
        $file = $request->file('participantes_file');
        try {
            $import = new ParticipantesImport;
            Excel::import($import, $file);
            $failures = $import->failures();
            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors()) . " (Valores: " . json_encode($failure->values()) . ")";
                }
                // Si hay errores de validación, los mostramos como 'warning' porque la importación se completó parcialmente.
                // El detalle de los errores se pasa en 'import_errors' para ser mostrado en la vista.
                return redirect()->route('participantes.import.form')
                             ->with('warning', 'Importación completada con algunos errores. Revisa los detalles.')
                             ->with('import_errors', $errorMessages);
            }
            return redirect()->route('participante.index')->with('success', 'Participantes importados exitosamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors()) . " (Valores: " . json_encode($failure->values()) . ")";
             }
             return redirect()->route('participantes.import.form')
                             ->with('error', 'Error de validación durante la importación. Ningún dato fue importado.')
                             ->with('import_errors', $errorMessages);
        } catch (\Exception $e) {
            Log::error('Error importando participantes: ' . $e->getMessage());
            return redirect()->route('participantes.import.form')->with('error', 'Ocurrió un error inesperado durante la importación: ' . $e->getMessage());
        }
    }
}
