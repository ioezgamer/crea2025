<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request; // Necesario para index, indexByGrade, getLugaresByPrograma, toggleActivo
use App\Http\Requests\StoreParticipanteRequest;
use App\Http\Requests\UpdateParticipanteRequest;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        $search_name = $request->input('search_name');
        $search_programa = $request->input('search_programa');
        $search_lugar = $request->input('search_lugar');
        $grado_filter = $request->input('grado'); // Puede ser null

        $query = Participante::query()
            ->filterByName($search_name)
            ->filterByPrograma($search_programa) // Scope modificado para CSV/LIKE
            ->filterByLugar($search_lugar);

        if ($grado_filter) {
            $query->filterByGrado(urldecode($grado_filter));
        }

        $query->orderBy('grado_p', 'asc')->orderBy('primer_nombre_p', 'asc');
            
        $participantes = $query->paginate($request->input('per_page', 15));
        
        // Usar el método del modelo para obtener las opciones de programa
        $programOptions = Participante::getDistinctProgramasOptions();
    
        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions) // Opciones para el desplegable de filtro
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('grado', $grado_filter); // Pasar el grado original (sin decodificar) para el input hidden
    }

    public function indexByGrade(Request $request, $gradoParam) // $gradoParam es el de la URL
    {
        $decodedGrado = urldecode($gradoParam);

        $search_name = $request->input('search_name');
        $search_programa = $request->input('search_programa');
        $search_lugar = $request->input('search_lugar');

        $query = Participante::query()
            ->filterByGrado($decodedGrado) // Filtro principal por grado de la URL
            ->filterByName($search_name)
            ->filterByPrograma($search_programa)
            ->filterByLugar($search_lugar);
    
        $query->orderBy('primer_nombre_p', 'asc'); 
    
        $participantes = $query->paginate($request->input('per_page', 15));

        // Usar el método del modelo para obtener las opciones de programa
        $programOptions = Participante::getDistinctProgramasOptions();
    
        return view('participante.index', compact('participantes'))
                ->with('programas', $programOptions)
                ->with('search_name', $search_name)
                ->with('search_programa', $search_programa)
                ->with('search_lugar', $search_lugar)
                ->with('grado', $gradoParam); // Pasar el grado original de la URL
    }
    
    public function getLugaresByPrograma(Request $request)
    {
        $programaFilter = $request->query('programa');
        
        $query = Participante::query();
        if ($programaFilter) {
            // Asumiendo que $programaFilter es un valor único que se busca dentro del CSV 'programa'
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$programaFilter]); // Para MySQL
            $query->where('programa', 'like', '%'. $programaFilter .'%'); // Alternativa general
        }
        
        $lugares = $query->select('lugar_de_encuentro_del_programa')
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->distinct()
            ->pluck('lugar_de_encuentro_del_programa')
            ->sort()
            ->values();

        return response()->json($lugares);
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
}
