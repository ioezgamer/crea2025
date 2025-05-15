<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request; // Necesario para index, indexByGrade, getLugaresByPrograma, toggleActivo
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
        $grado_filter = $request->input('grado'); 

        $query = Participante::query()
            ->filterByName($search_name)
            ->filterByPrograma($search_programa) 
            ->filterByLugar($search_lugar);

        if ($grado_filter) {
            $query->filterByGrado(urldecode($grado_filter));
        }

        $query->orderBy('grado_p', 'asc')->orderBy('primer_apellido_p')->orderBy('primer_nombre_p');
            
        $participantes = $query->paginate($request->input('per_page', 15));
        
        // Asegúrate que este método exista en tu modelo Participante y funcione correctamente
        $programOptions = Participante::getDistinctProgramasOptions(); 
    
        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions) 
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('grado', $grado_filter);
    }
    
    public function indexByGrade(Request $request, $gradoParam)
    {
        $decodedGrado = urldecode($gradoParam);
        $search_name = $request->input('search_name');
        $search_programa = $request->input('search_programa');
        $search_lugar = $request->input('search_lugar');

        $query = Participante::query()
            ->filterByGrado($decodedGrado) 
            ->filterByName($search_name)
            ->filterByPrograma($search_programa)
            ->filterByLugar($search_lugar);
    
        $query->orderBy('primer_apellido_p')->orderBy('primer_nombre_p'); 
        $participantes = $query->paginate($request->input('per_page', 15));
        $programOptions = Participante::getDistinctProgramasOptions();
    
        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions)
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('grado', $gradoParam); 
    }

    /**
     * Obtiene los lugares de encuentro basados en el programa seleccionado.
     * Esta función es llamada por AJAX desde la vista de índice de participantes.
     */
    public function getLugaresByPrograma(Request $request)
    {
        $programaFilter = $request->query('programa');
        Log::info('[getLugaresByPrograma] Iniciando búsqueda de lugares.', ['programa_solicitado' => $programaFilter]);

        if (empty($programaFilter)) {
            Log::warning('[getLugaresByPrograma] No se proporcionó filtro de programa. Devolviendo array vacío.');
            return response()->json([]);
        }

        try {
            // Obtener todos los participantes que podrían tener el programa (usando LIKE para campos CSV)
            // y que tengan un lugar de encuentro no nulo y no vacío.
            $participantesConPosiblePrograma = Participante::where('programa', 'LIKE', '%' . $programaFilter . '%')
                                                ->whereNotNull('lugar_de_encuentro_del_programa')
                                                ->where('lugar_de_encuentro_del_programa', '!=', '')
                                                ->select('programa', 'lugar_de_encuentro_del_programa') // Solo campos necesarios
                                                ->get();
            
            Log::info('[getLugaresByPrograma] Participantes preliminares encontrados con LIKE:', [
                'count' => $participantesConPosiblePrograma->count(),
                'programa_filtro' => $programaFilter
            ]);

            $lugaresFiltrados = collect();

            foreach ($participantesConPosiblePrograma as $participante) {
                // Convertir el campo 'programa' (CSV) del participante a un array de programas individuales
                $programasDelParticipante = array_map('trim', explode(',', $participante->programa ?? ''));
                
                // Verificar si el programa solicitado está exactamente en la lista de programas del participante
                if (in_array($programaFilter, $programasDelParticipante)) {
                    $lugaresFiltrados->push($participante->lugar_de_encuentro_del_programa);
                }
            }
            
            // Obtener lugares únicos, ordenarlos y devolverlos
            $lugares = $lugaresFiltrados->unique()->filter()->sort()->values();

            Log::info('[getLugaresByPrograma] Lugares finales procesados:', [
                'count' => $lugares->count(), 
                'lugares' => $lugares->toArray()
            ]);
            return response()->json($lugares);

        } catch (\Exception $e) {
            Log::error('[getLugaresByPrograma] Excepción durante la búsqueda de lugares:', [
                'mensaje_error' => $e->getMessage(),
                'programa_solicitado' => $programaFilter,
                'archivo_error' => $e->getFile(),
                'linea_error' => $e->getLine(),
                // 'traza_completa' => $e->getTraceAsString(), // Descomentar solo para depuración muy detallada
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

        $programaOptionsList = ['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca']; // Para selects de programa principal
        $subProgramaOptionsList = ['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM']; // Para selects de sub-programas
        $diasOptionsList = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']; // Para selects de días

        return view('participante.create', compact(
            'comunidades', 'tipos_tutor', 'sector_economico', 'nivel_educacion',
            'programaOptionsList', 'subProgramaOptionsList', 'diasOptionsList'
        ));
    }

    public function store(StoreParticipanteRequest $request)
    {
        $validated = $request->validated();
        $validated['dias_de_asistencia_al_programa'] = implode(',', $validated['dias_de_asistencia_al_programa']);
        $validated['programas'] = implode(',', $validated['programas']); 
        $validated['programa'] = implode(',', $validated['programa']);
        Participante::create($validated);
        return redirect()->route('participante.index')
                         ->with('success', 'Participante creado exitosamente.');
    }
    
    public function show(Participante $participante)
    {
        // Convertir CSVs a arrays para mostrar en la vista si es necesario
        $participante->dias_de_asistencia_al_programa_array = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
        $participante->programas_array = !empty($participante->programas) ? explode(',', $participante->programas) : [];
        $participante->programa_array = !empty($participante->programa) ? explode(',', $participante->programa) : [];
        return view('participante.show', compact('participante'));
    }

    public function exportPdf($id) 
    {
        \Carbon\Carbon::setLocale('es');
        $participante = Participante::findOrFail($id);
        // Convertir CSVs a arrays para el PDF si es necesario mostrar como lista
        $participante->dias_de_asistencia_al_programa_array = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
        $participante->programas_array = !empty($participante->programas) ? explode(',', $participante->programas) : [];
        $participante->programa_array = !empty($participante->programa) ? explode(',', $participante->programa) : [];

        $html = view('participante.pdf', compact('participante'))->render();
        $mpdf = new Mpdf([
            'format' => 'Letter','margin_top' => 10,'margin_bottom' => 10,
            'margin_left' => 15,'margin_right' => 15,
        ]);
        $mpdf->WriteHTML($html);
        $nombreArchivo = "Ficha_Participante_{$participante->primer_nombre_p}_{$participante->primer_apellido_p}.pdf";
        return $mpdf->Output($nombreArchivo, 'D');
    }
    
    public function edit(Participante $participante)
    {
        $comunidades = Participante::distinct()->pluck('comunidad_tutor')->filter()->sort()->values();
        $sector_economico = Participante::distinct()->pluck('sector_economico_tutor')->filter()->sort()->values();
        $nivel_educacion = Participante::distinct()->pluck('nivel_de_educacion_formal_adquirido_tutor')->filter()->sort()->values();
        $tipos_tutor_db = Participante::distinct()->pluck('tutor_principal')->filter()->sort()->values();
        $tipos_tutor_estaticos = ['Padre', 'Madre', 'Abuelo/a', 'Tío/a', 'Otro'];
        $tipos_tutor = $tipos_tutor_db->merge($tipos_tutor_estaticos)->unique()->sort()->values();
        
        // Convertir CSVs de la BD a arrays para los selects múltiples del formulario
        $participante->dias_de_asistencia_al_programa = !empty($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : [];
        $participante->programas = !empty($participante->programas) ? explode(',', $participante->programas) : [];
        $participante->programa = !empty($participante->programa) ? explode(',', $participante->programa) : [];

        $programaOptionsList = ['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'];
        $subProgramaOptionsList = ['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM'];
        $diasOptionsList = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        return view('participante.edit', compact(
            'participante', 'comunidades', 'tipos_tutor', 'sector_economico', 'nivel_educacion',
            'programaOptionsList', 'subProgramaOptionsList', 'diasOptionsList'
        ));
    }

    public function update(UpdateParticipanteRequest $request, Participante $participante)
    {
        $validated = $request->validated();
        $validated['dias_de_asistencia_al_programa'] = implode(',', $validated['dias_de_asistencia_al_programa']);
        $validated['programas'] = implode(',', $validated['programas']);
        $validated['programa'] = implode(',', $validated['programa']);
        $participante->update($validated);
        return redirect()->route('participante.index')->with('success', 'Participante actualizado correctamente.');
    }

    public function destroy(Participante $participante)
    {
        $participante->delete();
        return redirect()->route('participante.index')->with('success', 'Participante eliminado exitosamente.');
    }

    public function toggleActivo(Request $request)
    {
        $validated = $request->validate([
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'activo' => 'required|boolean'
        ]);
        try {
            Log::info('Iniciando toggleActivo', ['input' => $validated]);
            $participanteToToggle = Participante::findOrFail($validated['participante_id']); // Renombrada para evitar conflicto
            $participanteToToggle->activo = $validated['activo'];
            $updated = $participanteToToggle->save();
            Log::info('Resultado de update', [
                'participante_id' => $validated['participante_id'],
                'activo' => $validated['activo'],
                'updated_status' => $updated
            ]);
            if ($updated) {
                return response()->json(['success' => true,'message' => 'Estado actualizado correctamente.']);
            } else {
                Log::warning('No se actualizó ningún registro o el estado no cambió', ['participante_id' => $validated['participante_id']]);
                return response()->json(['success' => false,'message' => 'No se pudo actualizar el participante o el estado ya era el mismo.'], 422);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Participante no encontrado en toggleActivo', ['error' => $e->getMessage(), 'input' => $validated]);
            return response()->json(['success' => false, 'message' => 'Participante no encontrado.'], 404);
        } catch (\Exception $e) {
            Log::error('Error en toggleActivo', ['error' => $e->getMessage(),'input' => $validated]);
            return response()->json(['success' => false,'message' => 'Error al actualizar el estado: ' . $e->getMessage()], 500);
        }
    }

     public function exportParticipantes(Request $request)
    {
        // Obtener filtros de la request para pasarlos al export
        $filters = $request->only(['search_name', 'search_programa', 'search_lugar', 'grado']);

        $timestamp = now()->format('Y-m-d_H-i-s');
        return Excel::download(new ParticipantesExport($filters), "participantes_{$timestamp}.xlsx");
        // Para CSV: return Excel::download(new ParticipantesExport($filters), "participantes_{$timestamp}.csv", \Maatwebsite\Excel\Excel::CSV);
    }

    public function showImportForm()
    {
        // Simplemente muestra una vista con un formulario para subir el archivo
        return view('participante.import_form'); // Debes crear esta vista
    }

    public function importParticipantes(Request $request)
    {
        $request->validate([
            'participantes_file' => 'required|mimes:xlsx,xls,csv|max:20480' // Max 20MB
        ]);

        $file = $request->file('participantes_file');

        try {
            $import = new ParticipantesImport;
            Excel::import($import, $file);

            $failures = $import->failures(); // Obtener filas que fallaron la validación

            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Fila {$failure->row()}: " . implode(', ', $failure->errors()) . " (Valores: " . json_encode($failure->values()) . ")";
                }
                return redirect()->route('participantes.import.form')
                             ->with('warning', 'Importación completada con algunos errores.')
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
                             ->with('error', 'Error de validación durante la importación.')
                             ->with('import_errors', $errorMessages);
        } catch (\Exception $e) {
            \Log::error('Error importando participantes: ' . $e->getMessage());
            return redirect()->route('participantes.import.form')->with('error', 'Ocurrió un error durante la importación: ' . $e->getMessage());
        }
    }
}
