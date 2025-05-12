<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        // Iniciar la consulta
        $query = Participante::query();
    
        // Filtrar por nombre (primer_nombre_p o primer_apellido_p)
        if ($request->filled('search_name')) {
            $searchName = $request->input('search_name');
            $query->where(function ($q) use ($searchName) {
                $q->where('primer_nombre_p', 'like', '%'. $searchName .'%')
                  ->orWhere('primer_apellido_p', 'like', '%'. $searchName .'%');
            });
        }
    
        // Filtrar por programa
        if ($request->filled('search_programa')) {
            $searchPrograma = $request->input('search_programa');
            $query->where('programa', 'like', '%'. $searchPrograma .'%');
        }
    
        // Filtrar por lugar de encuentro
        if ($request->filled('search_lugar')) {
            $searchLugar = $request->input('search_lugar');
            $query->where('lugar_de_encuentro_del_programa', 'like', '%'. $searchLugar .'%');
        }

        if ($grado = request('grado')) {
            $query->where('grado_p', urldecode($grado));
        }
        // Ordenar por nombre (primer_nombre_p, primer_apellido_p) y grado (grado_p)
        $query->orderBy('grado_p', 'asc')
              ->orderBy('primer_nombre_p', 'asc');
    
        // Obtener todos los programas distintos
        $programas = Participante::select('programa')
            ->distinct()
            ->pluck('programa')
            ->filter()
            ->sort()
            ->values();
    
        // Obtener los participantes paginados
        $participantes = $query->paginate(request()->input('per_page', 15));

    
        return view('participante.index', compact('participantes', 'programas'));
    }

    public function getLugaresByPrograma(Request $request)
{
    $programa = $request->query('programa');
    
    $lugares = Participante::when($programa, function($query) use ($programa) {
            return $query->where('programa', $programa);
        })
        ->select('lugar_de_encuentro_del_programa')
        ->distinct()
        ->pluck('lugar_de_encuentro_del_programa')
        ->filter()
        ->sort()
        ->values();

    return response()->json($lugares);
}

    public function create()
{
    $comunidades = Participante::distinct()->pluck('comunidad_tutor')->filter()->sort();
    $sector_economico = Participante::distinct()->pluck('sector_economico_tutor')->filter()->sort();
    $nivel_educacion = Participante::distinct()->pluck('nivel_de_educacion_formal_adquirido_tutor')->filter()->sort();
    $tipos_tutor = Participante::distinct()->pluck('tutor_principal')->filter()->sort();
    $tipos_tutor_estaticos = ['Otro'];
    $tipos_tutor = $tipos_tutor->merge($tipos_tutor_estaticos)->unique()->sort();

    return view('participante.create', compact('comunidades', 'tipos_tutor', 'sector_economico', 'nivel_educacion'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_de_inscripcion' => 'required|date',
            'ano_de_inscripcion' => 'required|integer|min:1900|max:9999',
            'participante' => 'required|string',
            'partida_de_nacimiento' => 'required|boolean',
            'activo' => 'nullable|boolean',
            'boletin_o_diploma_2024' => 'required|boolean',
            'cedula_tutor' => 'required|boolean',
            'cedula_participante_adulto' => 'required|boolean',
            'programa' => 'required|array|min:1',
            'programa.*' => 'in:Exito Academico,Desarrollo Juvenil,Biblioteca',
            'programas' => 'required|array|min:1',
            'programas.*' => 'in:RAC,RACREA,CLC,CLCREA,DJ,BM,CLM',
            'lugar_de_encuentro_del_programa' => 'required|string',
            'primer_nombre_p' => 'required|string|max:255',
            'segundo_nombre_p' => 'nullable|string|max:255',
            'primer_apellido_p' => 'required|string|max:255',
            'segundo_apellido_p' => 'nullable|string|max:255',
            'ciudad_p' => 'nullable|string|max:255',
            'departamento_p' => 'nullable|string|max:255',
            'fecha_de_nacimiento_p' => 'required|date',
            'edad_p' => 'required|integer|min:0',
            'cedula_participante_adulto_str' => 'nullable|string|max:255',
            'genero' => 'required|string|max:255',
            'comunidad_p' => 'required|string|max:255',
            'escuela_p' => 'required|string|max:255',
            'comunidad_escuela' => 'required|string|max:255',
            'grado_p' => 'required|string|max:255',
            'turno' => 'nullable|string|max:255',
            'repite_grado' => 'nullable|boolean',
            'dias_de_asistencia_al_programa' => 'required|array|min:1',
            'dias_de_asistencia_al_programa.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'tutor_principal' => 'required|string|max:255',
            'nombres_y_apellidos_tutor_principal' => 'required|string|max:255',
            'numero_de_cedula_tutor' => 'nullable|string|max:255',
            'comunidad_tutor' => 'nullable|string|max:255',
            'direccion_tutor' => 'nullable|string|max:255',
            'telefono_tutor' => 'nullable|string|max:20',
            'sector_economico_tutor' => 'nullable|string|max:30',
            'nivel_de_educacion_formal_adquirido_tutor' => 'nullable|string|max:255',
            'expectativas_del_programa_tutor_principal' => 'nullable|string|max:255',
            'tutor_secundario' => 'nullable|string|max:255',
            'nombres_y_apellidos_tutor_secundario' => 'nullable|string|max:255',
            'numero_de_cedula_tutor_secundario' => 'nullable|string|max:255',
            'comunidad_tutor_secundario' => 'nullable|string|max:255',
            'telefono_tutor_secundario' => 'nullable|string|max:255',
            'asiste_a_otros_programas' => 'nullable|boolean',
            'otros_programas' => 'nullable|string',
            'dias_asiste_a_otros_programas' => 'nullable|integer|min:0',
        ]);
    
        // Convertir el array a string antes de guardar
        $validated['dias_de_asistencia_al_programa'] = implode(',', $validated['dias_de_asistencia_al_programa']);
        $validated['programas'] = implode(',', $request->input('programas', []));
        $validated['programa'] = implode(',', $validated['programa']);


        Participante::create($validated);
    
        return redirect()->route('participante.index')
        ->with('success', 'Participante creado exitosamente.');
    }
    

    public function show($id)
    {
        $participante = Participante::findOrFail($id);
        return view('participante.show', compact('participante'));
    }

    public function edit(Participante $participante)
{
    $comunidades = Participante::distinct()->pluck('comunidad_tutor')->filter()->sort();
    $tipos_tutor = Participante::distinct()->pluck('tutor_principal')->filter()->sort();
    return view('participante.edit', compact('participante', 'comunidades', 'tipos_tutor'));
}

    public function update(Request $request, Participante $participante)
{
    $validated = $request->validate([
        'fecha_de_inscripcion' => 'required|date',
        'ano_de_inscripcion' => 'required|integer|min:1900|max:9999',
        'participante' => 'required|in:primaria,secundaria',
        'partida_de_nacimiento' => 'required|boolean',
        'activo' => 'nullable|boolean',
        'boletin_o_diploma_2024' => 'required|boolean',
        'cedula_tutor' => 'required|boolean',
        'cedula_participante_adulto' => 'required|boolean',
        'programa' => 'required|array|min:1',
        'programa.*' => 'in:Exito Academico,Desarrollo Juvenil,Biblioteca',
        'programas' => 'required|array|min:1',
        'programas.*' => 'in:RAC,RACREA,CLC,CLCREA,DJ,BM,CLM',
        'lugar_de_encuentro_del_programa' => 'required|string',
        'primer_nombre_p' => 'required|string|max:255',
        'segundo_nombre_p' => 'nullable|string|max:255',
        'primer_apellido_p' => 'required|string|max:255',
        'segundo_apellido_p' => 'nullable|string|max:255',
        'ciudad_p' => 'nullable|string|max:255',
        'departamento_p' => 'nullable|string|max:255',
        'fecha_de_nacimiento_p' => 'required|date',
        'edad_p' => 'required|integer|min:0',
        'cedula_participante_adulto_str' => 'nullable|string|max:255',
        'genero' => 'required|string|max:255',
        'comunidad_p' => 'required|string|max:255',
        'escuela_p' => 'required|string|max:255',
        'comunidad_escuela' => 'required|string|max:255',
        'grado_p' => 'required|string|max:255',
        'turno' => 'nullable|string|max:255',
        'repite_grado' => 'nullable|boolean',
        'dias_de_asistencia_al_programa' => 'required|array|min:1',
        'dias_de_asistencia_al_programa.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes',
        'tutor_principal' => 'required|string|max:255',
        'nombres_y_apellidos_tutor_principal' => 'required|string|max:255',
        'numero_de_cedula_tutor' => 'nullable|string|max:255',
        'comunidad_tutor' => 'nullable|string|max:255',
        'direccion_tutor' => 'nullable|string|max:255',
        'telefono_tutor' => 'nullable|string|max:20',
        'sector_economico_tutor' => 'nullable|string|max:30',
        'nivel_de_educacion_formal_adquirido_tutor' => 'nullable|string|max:255',
        'expectativas_del_programa_tutor_principal' => 'nullable|string|max:255',
        'tutor_secundario' => 'nullable|string|max:255',
        'nombres_y_apellidos_tutor_secundario' => 'nullable|string|max:255',
        'numero_de_cedula_tutor_secundario' => 'nullable|string|max:255',
        'comunidad_tutor_secundario' => 'nullable|string|max:255',
        'telefono_tutor_secundario' => 'nullable|string|max:255',
        'asiste_a_otros_programas' => 'nullable|boolean',
        'otros_programas' => 'nullable|string',
        'dias_asiste_a_otros_programas' => 'nullable|integer|min:0',
    ]);

    // Convertir arrays a strings
    $validated['dias_de_asistencia_al_programa'] = implode(',', $request->input('dias_de_asistencia_al_programa', []));
    $validated['programas'] = implode(',', $request->input('programas', []));
    $validated['programa'] = implode(',', $request->input('programa', []));

    $participante->update($validated);

    return redirect()->route('participante.index')->with('success', 'Participante actualizado correctamente.');
}


    public function destroy(Participante $participante)
    {
        $participante->delete();
        return redirect()->route('participante.index')->with('success', 'Participant deleted successfully.');
    }


    // En app/Http/Controllers/ParticipanteController.php
    public function indexByGrade($grado)
    {
        $query = Participante::query();
    
        // Filter by grade
        $query->where('grado_p', urldecode($grado));
    
        // Filter by name (primer_nombre_p or primer_apellido_p)
        if ($searchName = request('search_name')) {
            $query->where(function ($q) use ($searchName) {
                $q->where('primer_nombre_p', 'like', '%' . $searchName . '%')
                  ->orWhere('primer_apellido_p', 'like', '%' . $searchName . '%');
            });
        }
    
        // Filter by program
        if ($searchPrograma = request('search_programa')) {
            $query->where('programa', 'like', '%' . $searchPrograma . '%');
        }
    
        // Filter by place
        if ($searchLugar = request('search_lugar')) {
            $query->where('lugar_de_encuentro_del_programa', 'like', '%' . $searchLugar . '%');
        }
    
        // Sort by grade and name
        $query->orderBy('grado_p', 'asc')
              ->orderBy('primer_nombre_p', 'asc');
    
        // Get distinct programs
        $programas = Participante::select('programa')
            ->distinct()
            ->pluck('programa')
            ->filter()
            ->sort()
            ->values();
    
        // Paginate participants
        $participantes = $query->paginate(request()->input('per_page', 15));
    
        return view('participante.index', compact('participantes', 'programas'));
    }

    public function toggleActivo(Request $request)
{
    try {
        $request->validate([
            'participante_id' => 'required|exists:participantes,participante_id',
            'activo' => 'required|boolean'
        ]);

        $participante = Participante::findOrFail($request->participante_id);
        $participante->activo = $request->activo;
        $participante->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el estado: ' . $e->getMessage()
        ], 500);
    }
}
}