<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Participante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Para validación manual en AJAX

// Mpdf si lo usas directamente, aunque es mejor en un servicio o a través de un paquete de PDF
// use Mpdf\Mpdf;

class AsistenciaController extends Controller
{
    // Método para mostrar la vista principal de toma de asistencia
    public function create(Request $request)
    {
        // Valores iniciales de los filtros (pueden venir de la URL o ser defaults)
        $selectedPrograma = $request->input('programa', '');
        $selectedLugar = $request->input('lugar_de_encuentro_del_programa', '');
        $selectedGrado = $request->input('grado_p', '');
        $fechaInicioInput = $request->input('fecha_inicio', now()->startOfWeek()->format('Y-m-d'));

        try {
            $fechaInicioCarbon = Carbon::parse($fechaInicioInput);
            if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
                // Si la fecha no es lunes, se ajusta al lunes anterior o se muestra un error.
                // Por simplicidad, aquí podríamos forzarlo o devolver un error.
                // Devolver error es más informativo para el usuario.
                return redirect()->route('asistencia.create', $request->except('fecha_inicio'))
                    ->withErrors(['fecha_inicio' => 'La fecha de inicio debe ser un lunes.'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->route('asistencia.create', $request->except('fecha_inicio'))
                ->withErrors(['fecha_inicio' => 'El formato de la fecha de inicio no es válido.'])
                ->withInput();
        }
        
        $fechaInicio = $fechaInicioCarbon->format('Y-m-d');

        // Opciones para los filtros principales (siempre se cargan)
        // Si 'programa' en Participante es CSV, necesitamos procesarlo para obtener opciones únicas
        $programOptions = Participante::getDistinctProgramasOptions(); // Asumiendo que este método existe en el modelo Participante

        // Los lugares y grados se cargarán dinámicamente con AJAX,
        // pero podemos cargar los iniciales si hay un programa seleccionado.
        $lugarOptions = [];
        if ($selectedPrograma) {
            $lugarOptions = $this->getLugaresEncuentroQuery($selectedPrograma)->get()->pluck('lugar_de_encuentro_del_programa');
        }

        $gradoOptions = [];
        if ($selectedPrograma && $selectedLugar) {
            $gradoOptions = $this->getGradosQuery($selectedPrograma, $selectedLugar)->get()->pluck('grado_p');
        }
        
        // Generar los días de la semana (Lunes a Viernes)
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            // Usar translatedFormat para el nombre del día en español si Carbon está configurado para 'es'
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        // Los participantes y sus asistencias se cargarán vía AJAX al seleccionar todos los filtros.
        // Opcionalmente, puedes cargar una lista inicial si todos los filtros ya están seteados.
        $participantes = collect(); // Colección vacía inicialmente
        $asistencias = [];

        if ($selectedPrograma && $selectedLugar && $selectedGrado && $fechaInicio) {
             list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
                $selectedPrograma, $selectedLugar, $selectedGrado, $fechaInicioCarbon, $diasSemana
            );
        }
        
        return view('asistencia.attendance', compact(
            'programOptions', 'lugarOptions', 'gradoOptions',
            'selectedPrograma', 'selectedLugar', 'selectedGrado',
            'fechaInicio', 'diasSemana',
            'participantes', 'asistencias' // Estos pueden estar vacíos inicialmente
        ));
    }

    // Método privado para cargar participantes y sus asistencias
    private function cargarParticipantesYAsistencias($programa, $lugar, $grado, Carbon $fechaInicioCarbon, $diasSemana)
    {
        $query = Participante::query()->where('activo', true);

        // Aplicar filtros
        // Si 'programa' es CSV en la BD, usar FIND_IN_SET (MySQL) o LIKE
        // $query->whereRaw('FIND_IN_SET(?, programa)', [$programa]);
        $query->where('programa', 'like', '%' . $programa . '%'); // Asumiendo que el filtro es un programa único

        if ($lugar) {
            $query->where('lugar_de_encuentro_del_programa', $lugar);
        }
        if ($grado) {
            $query->where('grado_p', $grado);
        }
        
        // Eager load asistencias para el rango de fechas para optimizar
        $fechaInicioSemana = $fechaInicioCarbon->format('Y-m-d');
        $fechaFinSemana = $fechaInicioCarbon->copy()->addDays(4)->format('Y-m-d');

        $participantes = $query->with(['asistencias' => function ($q) use ($fechaInicioSemana, $fechaFinSemana) {
            $q->whereBetween('fecha_asistencia', [$fechaInicioSemana, $fechaFinSemana]);
        }])->orderBy('primer_apellido_p')->orderBy('primer_nombre_p')->get();

        $asistenciasData = [];
        foreach ($participantes as $participante) {
            $asistenciasParticipante = $participante->asistencias->keyBy(function ($item) {
                return Carbon::parse($item->fecha_asistencia)->format('Y-m-d');
            });

            $totalAsistido = 0;
            foreach ($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistenciasParticipante->get($fechaDia)?->estado ?? 'Ausente'; // Default a Ausente
                $asistenciasData[$participante->participante_id][$diaNombre] = $estado;
                if ($estado === 'Presente') {
                    $totalAsistido++;
                }
            }
            $participante->totalAsistido = $totalAsistido;
            $participante->porcentajeAsistencia = count($diasSemana) > 0
                ? round(($totalAsistido / count($diasSemana)) * 100)
                : 0;
        }
        return [$participantes, $asistenciasData];
    }


    // --- Métodos para Filtros Dinámicos AJAX ---
    private function getLugaresEncuentroQuery($programa)
    {
        $query = Participante::select('lugar_de_encuentro_del_programa')->distinct()
            ->whereNotNull('lugar_de_encuentro_del_programa')
            ->where('lugar_de_encuentro_del_programa', '!=', '');
        if ($programa) {
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$programa]); // MySQL
            $query->where('programa', 'like', '%' . $programa . '%');
        }
        return $query->orderBy('lugar_de_encuentro_del_programa');
    }

    public function getLugaresEncuentro(Request $request)
    {
        $validator = Validator::make($request->all(), ['programa' => 'required|string']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $programa = $request->query('programa');
        $lugares = $this->getLugaresEncuentroQuery($programa)->get()->pluck('lugar_de_encuentro_del_programa');
        return response()->json($lugares);
    }

    private function getGradosQuery($programa, $lugar)
    {
        $query = Participante::select('grado_p')->distinct()
            ->whereNotNull('grado_p')->where('grado_p', '!=', '');
        if ($programa) {
            // $query->whereRaw('FIND_IN_SET(?, programa)', [$programa]); // MySQL
            $query->where('programa', 'like', '%' . $programa . '%');
        }
        if ($lugar) {
            $query->where('lugar_de_encuentro_del_programa', $lugar);
        }
        return $query->orderBy('grado_p');
    }

    public function getGrados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $programa = $request->query('programa');
        $lugar = $request->query('lugar_de_encuentro_del_programa');
        $grados = $this->getGradosQuery($programa, $lugar)->get()->pluck('grado_p');
        return response()->json($grados);
    }
    
    // Método AJAX para obtener la tabla de participantes filtrados
    public function getParticipantesFiltrados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'required|string',
            'grado_p' => 'required|string',
            'fecha_inicio' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'details' => $validator->errors()], 400);
        }

        $selectedPrograma = $request->input('programa');
        $selectedLugar = $request->input('lugar_de_encuentro_del_programa');
        $selectedGrado = $request->input('grado_p');
        $fechaInicioInput = $request->input('fecha_inicio');
        
        try {
            $fechaInicioCarbon = Carbon::parse($fechaInicioInput);
            if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
                 return response()->json(['error' => 'La fecha de inicio debe ser un lunes.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Formato de fecha inválido.'], 400);
        }

        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $selectedPrograma, $selectedLugar, $selectedGrado, $fechaInicioCarbon, $diasSemana
        );

        // Retornar la vista parcial de la tabla de asistencia
        $htmlTabla = view('asistencia.partials.tabla_asistencia', compact('participantes', 'diasSemana', 'asistencias', 'selectedPrograma', 'fechaInicioInput', 'selectedLugar', 'selectedGrado'))->render();
        
        return response()->json(['html' => $htmlTabla]);
    }


    // Método para guardar una asistencia individual (AJAX)
    public function storeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'fecha_asistencia' => 'required|date_format:Y-m-d',
            'estado' => 'required|in:Presente,Ausente,Justificado',
            // Podrías añadir los filtros originales si necesitas devolverlos para actualizar algo más
            // 'programa' => 'required|string',
            // 'fecha_inicio_semana' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            Asistencia::updateOrCreate(
                [
                    'participante_id' => $request->input('participante_id'),
                    'fecha_asistencia' => $request->input('fecha_asistencia'),
                ],
                [
                    'estado' => $request->input('estado'),
                ]
            );

            DB::commit();

            // Calcular nuevos totales para este participante en esta semana (opcional, pero útil para feedback)
            // Necesitarías la fecha de inicio de la semana para esto.
            // Si no la pasas, el feedback de totales lo manejará el JS en cliente.

            return response()->json(['success' => true, 'message' => 'Asistencia actualizada.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar asistencia individual: ' . $e->getMessage(), $request->all());
            return response()->json(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }
    
    // Los métodos store (original), reporte y exportPdf deben ser revisados y adaptados
    // si la lógica principal de toma de asistencia cambia a ser por celda AJAX.
    // El store original podría eliminarse o adaptarse para un guardado masivo si aún se desea.
    // Por ahora, los comentaré o simplificaré para enfocarnos en la nueva UX.

    /*
    public function store(Request $request) // Este método podría ya no ser necesario o cambiar su propósito
    {
        // ... Lógica anterior de guardado masivo por participante ...
        // Si se mantiene, asegurar que la validación sea exhaustiva.
    }
    */

    public function reporte(Request $request)
    {
        // Validar filtros
        $filters = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
            'grado_p' => 'nullable|string',
            'fecha_inicio' => 'required|date_format:Y-m-d',
        ])->validate(); // Lanza excepción si falla

        $fechaInicioCarbon = Carbon::parse($filters['fecha_inicio']);
        if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
            return redirect()->route('asistencia.reporte', $request->except('fecha_inicio'))
                ->withErrors(['fecha_inicio' => 'La fecha de inicio para el reporte debe ser un lunes.'])
                ->withInput();
        }
        
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $filters['programa'],
            $filters['lugar_de_encuentro_del_programa'],
            $filters['grado_p'],
            $fechaInicioCarbon,
            $diasSemana
        );

        // Calcular estadísticas generales para el reporte
        $estadisticasPorDia = array_fill_keys(array_keys($diasSemana), ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Total' => 0]);
        foreach($participantes as $p) {
            foreach($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistencias[$p->participante_id][$diaNombre] ?? 'Ausente';
                if (isset($estadisticasPorDia[$diaNombre][$estado])) {
                    $estadisticasPorDia[$diaNombre][$estado]++;
                }
                $estadisticasPorDia[$diaNombre]['Total']++;
            }
        }
        $totalParticipantes = $participantes->count();
        $sumaPorcentajes = $participantes->sum('porcentajeAsistencia');
        $promedioAsistenciaGeneral = $totalParticipantes > 0 ? round($sumaPorcentajes / $totalParticipantes) : 0;
        
        // Opciones para los filtros en la vista de reporte
        $programOptions = Participante::getDistinctProgramasOptions();
        $lugarOptions = $this->getLugaresEncuentroQuery($filters['programa'])->get()->pluck('lugar_de_encuentro_del_programa');
        $gradoOptions = $this->getGradosQuery($filters['programa'], $filters['lugar_de_encuentro_del_programa'])->get()->pluck('grado_p');


        return view('asistencia.reporte', compact(
            'participantes', 'asistencias', 'diasSemana',
            'filters', // Pasar los filtros actuales a la vista
            'programOptions', 'lugarOptions', 'gradoOptions', // Opciones para los selects de filtro
            'estadisticasPorDia', 'totalParticipantes', 'promedioAsistenciaGeneral'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Similar al método reporte, pero genera PDF
        $filters = Validator::make($request->all(), [
            'programa' => 'required|string',
            'lugar_de_encuentro_del_programa' => 'nullable|string',
            'grado_p' => 'nullable|string',
            'fecha_inicio' => 'required|date_format:Y-m-d',
        ])->validate();

        $fechaInicioCarbon = Carbon::parse($filters['fecha_inicio']);
        if ($fechaInicioCarbon->dayOfWeek !== Carbon::MONDAY) {
             return redirect()->route('asistencia.reporte', $request->except('fecha_inicio')) // O a donde sea apropiado
                ->withErrors(['fecha_inicio_export' => 'La fecha de inicio para el PDF debe ser un lunes.'])
                ->withInput();
        }
        
        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = $fechaInicioCarbon->copy()->addDays($i);
            $diasSemana[$fecha->translatedFormat('l')] = $fecha->format('Y-m-d');
        }

        list($participantes, $asistencias) = $this->cargarParticipantesYAsistencias(
            $filters['programa'],
            $filters['lugar_de_encuentro_del_programa'],
            $filters['grado_p'],
            $fechaInicioCarbon,
            $diasSemana
        );
        
        // Calcular estadísticas para el PDF (similar a reporte)
        $estadisticasPorDia = array_fill_keys(array_keys($diasSemana), ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Total' => 0]);
        foreach($participantes as $p) {
            foreach($diasSemana as $diaNombre => $fechaDia) {
                $estado = $asistencias[$p->participante_id][$diaNombre] ?? 'Ausente';
                 if (isset($estadisticasPorDia[$diaNombre][$estado])) {
                    $estadisticasPorDia[$diaNombre][$estado]++;
                }
                $estadisticasPorDia[$diaNombre]['Total']++;
            }
        }
        $totalParticipantes = $participantes->count();
        $sumaPorcentajes = $participantes->sum('porcentajeAsistencia');
        $promedioAsistenciaGeneral = $totalParticipantes > 0 ? round($sumaPorcentajes / $totalParticipantes) : 0;

        // Asegúrate que la vista 'asistencia.pdf' exista y esté preparada para estos datos
        $html = view('asistencia.pdf', compact(
            'filters', 'participantes', 'diasSemana', 'asistencias',
            'totalParticipantes', 'promedioAsistenciaGeneral', 'estadisticasPorDia'
        ))->render();

        try {
            $mpdf = new \Mpdf\Mpdf([
                'format' => 'A4-L', 
                'margin_top' => 10, 'margin_bottom' => 10,
                'margin_left' => 10, 'margin_right' => 10,
            ]);
            $mpdf->WriteHTML($html);
            $fechaFormateada = $fechaInicioCarbon->format('Y-m-d');
            $nombreArchivo = "Reporte_Asistencias_{$filters['programa']}_{$fechaFormateada}.pdf";
            return $mpdf->Output($nombreArchivo, 'D');
        } catch (\Mpdf\MpdfException $e) {
            Log::error("Error al generar PDF de asistencia: " . $e->getMessage());
            return redirect()->back()->withErrors(['pdf_error' => 'No se pudo generar el PDF: ' . $e->getMessage()]);
        }
    }
}