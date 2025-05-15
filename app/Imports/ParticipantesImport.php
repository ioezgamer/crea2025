<?php

namespace App\Imports;

use App\Models\Participante;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ParticipantesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Log::info('Importando fila:', $row);

        // Función auxiliar para parsear fechas de manera flexible
        $parseDate = function ($dateString) {
            if (empty($dateString)) return null;
            try {
                // Intenta con formato específico primero si es común
                return Carbon::createFromFormat('Y-m-d', $dateString)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    // Intento más general
                    return Carbon::parse($dateString)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Formato de fecha inválido: " . $dateString . " Error: " . $e->getMessage());
                    return null; // O manejar el error de otra forma
                }
            }
        };

        // Función auxiliar para convertir a booleano (1/0, true/false, 'si'/'no')
        $parseBoolean = function ($value) {
           if (is_null($value)) return null;
           if (is_bool($value)) return $value;
           $val = strtolower(trim((string)$value));
           if (in_array($val, ['1', 'true', 'si', 'yes', 'verdadero'])) return true;
           if (in_array($val, ['0', 'false', 'no', 'falso'])) return false;
           return null; // O default a false/true según prefieras
        };


        return new Participante([
            'fecha_de_inscripcion' => $parseDate($row['fecha_inscripcion'] ?? null),
            'ano_de_inscripcion' => $row['ano_inscripcion'] ?? null,
            'participante' => $row['tipo_participante'] ?? null,
            'partida_de_nacimiento' => $parseBoolean($row['tiene_partida_nacimiento_10'] ?? false),
            'boletin_o_diploma_2024' => $parseBoolean($row['tiene_boletindiploma_2024_10'] ?? false),
            'cedula_tutor' => $parseBoolean($row['tutor_presento_cedula_10'] ?? false),
            'cedula_participante_adulto' => $parseBoolean($row['participante_adulto_presento_cedula_10'] ?? false),
            'programa' => $row['programa_principal_csv'] ?? null,
            'programas' => $row['sub_programascodigos_csv'] ?? null,
            'lugar_de_encuentro_del_programa' => $row['lugar_encuentro_programa'] ?? null,
            'primer_nombre_p' => $row['primer_nombre'] ?? null,
            'segundo_nombre_p' => $row['segundo_nombre'] ?? null,
            'primer_apellido_p' => $row['primer_apellido'] ?? null,
            'segundo_apellido_p' => $row['segundo_apellido'] ?? null,
            'ciudad_p' => $row['ciudad_nacimiento'] ?? null,
            'departamento_p' => $row['departamento_nacimiento'] ?? null,
            'fecha_de_nacimiento_p' => $parseDate($row['fecha_nacimiento'] ?? null),
            'edad_p' => $row['edad'] ?? null, // Considerar calcularla si se omite
            'cedula_participante_adulto_str' => $row['cedula_participante_adulto_numero'] ?? null,
            'genero' => $row['genero'] ?? null,
            'comunidad_p' => $row['comunidad_residencia_participante'] ?? null,
            'escuela_p' => $row['escuela'] ?? null,
            'comunidad_escuela' => $row['comunidad_escuela'] ?? null,
            'grado_p' => $row['grado_escolar'] ?? null,
            'turno' => $row['turno_escolar'] ?? null,
            'repite_grado' => $parseBoolean($row['repite_grado_10'] ?? false),
            'dias_de_asistencia_al_programa' => $row['dias_asistencia_programa_csv'] ?? null,
            'tutor_principal' => $row['relacion_tutor_principal'] ?? null,
            'nombres_y_apellidos_tutor_principal' => $row['nombres_y_apellidos_tutor_principal'] ?? null,
            'numero_de_cedula_tutor' => $row['numero_cedula_tutor_principal'] ?? null,
            'comunidad_tutor' => $row['comunidad_tutor_principal'] ?? null,
            'direccion_tutor' => $row['direccion_tutor_principal'] ?? null,
            'telefono_tutor' => $row['telefono_tutor_principal'] ?? null,
            'sector_economico_tutor' => $row['sector_economico_tutor_principal'] ?? null,
            'nivel_de_educacion_formal_adquirido_tutor' => $row['nivel_educacion_tutor_principal'] ?? null,
            'expectativas_del_programa_tutor_principal' => $row['expectativas_tutor_principal'] ?? null,
            'tutor_secundario' => $row['relacion_tutor_secundario'] ?? null,
            'nombres_y_apellidos_tutor_secundario' => $row['nombres_y_apellidos_tutor_secundario'] ?? null,
            'numero_de_cedula_tutor_secundario' => $row['numero_cedula_tutor_secundario'] ?? null,
            'comunidad_tutor_secundario' => $row['comunidad_tutor_secundario'] ?? null,
            'telefono_tutor_secundario' => $row['telefono_tutor_secundario'] ?? null,
            'asiste_a_otros_programas' => $parseBoolean($row['asiste_otros_programas_10'] ?? false),
            'otros_programas' => $row['nombres_otros_programas'] ?? null,
            'dias_asiste_a_otros_programas' => $row['dias_asiste_otros_programas'] ?? null,
            'activo' => $parseBoolean($row['activo_10'] ?? true), // Default a activo
        ]);
    }

    public function rules(): array
    {
        // Los nombres de las claves deben coincidir con los encabezados de tu archivo Excel/CSV
        return [
            'primer_nombre' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'fecha_inscripcion' => 'nullable|date_format:Y-m-d',
            'ano_inscripcion' => 'nullable|integer|min:1900|max:'.(date('Y')+1),
            'tipo_participante' => 'nullable|string|max:255',
            'tiene_partida_nacimiento_10' => 'nullable|boolean_strict', // Para 1/0, true/false
            'tiene_boletindiploma_2024_10' => 'nullable|boolean_strict',
            'tutor_presento_cedula_10' => 'nullable|boolean_strict',
            'participante_adulto_presento_cedula_10' => 'nullable|boolean_strict',
            'programa_principal_csv' => 'required|string',
            'sub_programascodigos_csv' => 'nullable|string',
            'lugar_encuentro_programa' => 'nullable|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'ciudad_nacimiento' => 'nullable|string|max:255',
            'departamento_nacimiento' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date_format:Y-m-d',
            'edad' => 'nullable|integer|min:0',
            'cedula_participante_adulto_numero' => 'nullable|string|max:255|unique:participantes,cedula_participante_adulto_str,NULL,participante_id', // Ajustar si es necesario
            'genero' => 'nullable|string|max:255', // Considerar Rule::in(['Masculino', 'Femenino', 'Otro'])
            'comunidad_residencia_participante' => 'nullable|string|max:255',
            'escuela' => 'nullable|string|max:255',
            'comunidad_escuela' => 'nullable|string|max:255',
            'grado_escolar' => 'nullable|string|max:255',
            'turno_escolar' => 'nullable|string|max:255',
            'repite_grado_10' => 'nullable|boolean_strict',
            'dias_asistencia_programa_csv' => 'nullable|string',
            'relacion_tutor_principal' => 'nullable|string|max:255',
            'nombres_y_apellidos_tutor_principal' => 'nullable|string|max:255',
            'numero_cedula_tutor_principal' => 'nullable|string|max:255',
            'comunidad_tutor_principal' => 'nullable|string|max:255',
            'direccion_tutor_principal' => 'nullable|string|max:255',
            'telefono_tutor_principal' => 'nullable|string|max:20',
            'sector_economico_tutor_principal' => 'nullable|string|max:30',
            'nivel_educacion_tutor_principal' => 'nullable|string|max:255',
            'expectativas_tutor_principal' => 'nullable|string',
            'relacion_tutor_secundario' => 'nullable|string|max:255',
            'nombres_y_apellidos_tutor_secundario' => 'nullable|string|max:255',
            'numero_cedula_tutor_secundario' => 'nullable|string|max:255',
            'comunidad_tutor_secundario' => 'nullable|string|max:255',
            'telefono_tutor_secundario' => 'nullable|string|max:255',
            'asiste_otros_programas_10' => 'nullable|boolean_strict',
            'nombres_otros_programas' => 'nullable|string',
            'dias_asiste_otros_programas' => 'nullable|integer|min:0',
            'activo_10' => 'nullable|boolean_strict',

            // Para validar todas las filas con un prefijo:
            // '*.primer_nombre' => 'required|string|max:255',
        ];
    }

    // Regla personalizada para booleanos flexibles
    public function __construct()
    {
        Validator::extend('boolean_strict', function ($attribute, $value, $parameters, $validator) {
           if (is_null($value) && in_array('nullable', $validator->getRules()[$attribute])) return true;
           return in_array(strtolower((string)$value), ['true', 'false', '1', '0', 'si', 'no'], true);
        }, 'El campo :attribute debe ser verdadero/falso, 1/0 o si/no.');
    }


    public function customValidationMessages()
    {
        return [
            'primer_nombre.required' => 'El primer nombre es obligatorio en la fila.',
            'primer_apellido.required' => 'El primer apellido es obligatorio en la fila.',
            'programa_principal_csv.required' => 'El programa principal (CSV) es obligatorio en la fila.',
            '*.date_format' => 'El formato de fecha para :attribute debe ser YYYY-MM-DD.',
            '*.boolean_strict' => 'El valor para :attribute debe ser 1/0, true/false, o si/no.',
            '*.unique' => 'El valor para :attribute ya existe en la base de datos.',
        ];
    }
}