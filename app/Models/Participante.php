<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Necesario para DB::raw en algunos casos

class Participante extends Model
{
    use HasFactory;
    protected $table = "participantes";
    protected $primaryKey = 'participante_id';

    protected $fillable = [
        'fecha_de_inscripcion', 'ano_de_inscripcion', 'participante', 'partida_de_nacimiento',
        'boletin_o_diploma_2024', 'cedula_tutor', 'cedula_participante_adulto', 'programas', // Sub-programas (CSV)
        'lugar_de_encuentro_del_programa', 'primer_nombre_p', 'segundo_nombre_p',
        'primer_apellido_p', 'segundo_apellido_p', 'ciudad_p', 'departamento_p',
        'fecha_de_nacimiento_p', 'edad_p', 'cedula_participante_adulto_str', 'genero',
        'comunidad_p', 'escuela_p', 'comunidad_escuela', 'grado_p', 'turno', 'repite_grado',
        'dias_de_asistencia_al_programa', // CSV
        'programa', // Programa principal (CSV de 'Exito Academico', 'Desarrollo Juvenil', 'Biblioteca')
        'tutor_principal', 'nombres_y_apellidos_tutor_principal', 'numero_de_cedula_tutor',
        'comunidad_tutor', 'direccion_tutor', 'telefono_tutor', 'sector_economico_tutor',
        'nivel_de_educacion_formal_adquirido_tutor', 'expectativas_del_programa_tutor_principal',
        'tutor_secundario', 'nombres_y_apellidos_tutor_secundario', 'numero_de_cedula_tutor_secundario',
        'comunidad_tutor_secundario', 'telefono_tutor_secundario', 'asiste_a_otros_programas',
        'otros_programas', 'activo', 'dias_asiste_a_otros_programas',
    ];

    protected $casts = [
        'activo' => 'boolean', 'partida_de_nacimiento' => 'boolean',
        'boletin_o_diploma_2024' => 'boolean', 'cedula_tutor' => 'boolean',
        'cedula_participante_adulto' => 'boolean', 'repite_grado' => 'boolean',
        'asiste_a_otros_programas' => 'boolean',
        'fecha_de_inscripcion' => 'date:Y-m-d',
        'fecha_de_nacimiento_p' => 'date:Y-m-d',
        // NO castear a array aquí si se guardan como CSV y se manejan con explode/implode.
        // Si se migrara a JSON column type, entonces sí:
        // 'dias_de_asistencia_al_programa' => 'array',
        // 'programa' => 'array',
        // 'programas' => 'array',
    ];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'participante_id', 'participante_id');
    }

    // --- SCOPES ---

    public function scopeFilterByName($query, $name)
    {
        if ($name) {
            return $query->where(function ($q) use ($name) {
                $q->where('primer_nombre_p', 'like', '%'. $name .'%')
                  ->orWhere('primer_apellido_p', 'like', '%'. $name .'%')
                  ->orWhere('segundo_nombre_p', 'like', '%'. $name .'%')
                  ->orWhere('segundo_apellido_p', 'like', '%'. $name .'%');
            });
        }
        return $query;
    }

    /**
     * Scope para filtrar por programa principal (campo 'programa').
     * Asume que 'programa' en la BD puede ser un CSV y $filterPrograma es un valor único a buscar.
     */
    public function scopeFilterByPrograma($query, $filterPrograma)
    {
        if ($filterPrograma) {
            // Para MySQL, FIND_IN_SET es ideal para buscar en campos CSV
            // return $query->whereRaw('FIND_IN_SET(?, programa)', [$filterPrograma]);

            // Alternativa más general (pero menos precisa y eficiente) es LIKE
            // Esto encontrará 'Programa A' si el campo es 'Programa ABC, Programa A, Programa D'
            // pero también si el campo es 'Super Programa Avanzado' y el filtro es 'Programa A'
            return $query->where('programa', 'like', '%' . $filterPrograma . '%');
        }
        return $query;
    }

    public function scopeFilterByLugar($query, $lugar)
    {
        if ($lugar) {
            // Asumiendo que lugar_de_encuentro_del_programa es un string único y no CSV
            return $query->where('lugar_de_encuentro_del_programa', 'like', '%'. $lugar .'%');
            // Si fuera para coincidencia exacta:
            // return $query->where('lugar_de_encuentro_del_programa', $lugar);
        }
        return $query;
    }

    public function scopeFilterByGrado($query, $grado)
    {
        if ($grado) {
            // urldecode se hace en el controlador antes de pasar a este scope
            return $query->where('grado_p', $grado);
        }
        return $query;
    }

    /**
     * Obtiene una lista única de todos los programas individuales
     * presentes en el campo 'programa' (que es un CSV).
     */
    public static function getDistinctProgramasOptions()
    {
        $allProgramasStrings = self::whereNotNull('programa')
                                ->where('programa', '!=', '')
                                ->pluck('programa')
                                ->toArray();
        $programOptions = [];
        foreach ($allProgramasStrings as $programasCsv) {
            $individuales = explode(',', $programasCsv);
            foreach ($individuales as $prog) {
                $trimmedProg = trim($prog);
                if (!empty($trimmedProg)) {
                    $programOptions[] = $trimmedProg;
                }
            }
        }
        $programOptions = array_values(array_unique($programOptions)); // array_values para reindexar
        sort($programOptions);
        return $programOptions;
    }
    public function getRouteKeyName()
{
    return 'participante_id';
}

}
