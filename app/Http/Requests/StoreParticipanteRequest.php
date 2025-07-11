<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParticipanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Aquí puedes añadir lógica de autorización si es necesario
        // Por ejemplo, verificar si el usuario tiene permiso para crear participantes.
        // Por ahora, lo dejamos en true para permitir la acción.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_de_inscripcion' => 'required|date',
            'ano_de_inscripcion' => 'required|integer|min:1900|max:'.date('Y'),
            'participante' => 'required|string', // Asumo que es un tipo de participante, ej: 'primaria', 'secundaria'
            'partida_de_nacimiento' => 'required|boolean',
            'activo' => 'required|boolean',
            'boletin_o_diploma_2024' => 'required|boolean',
            'cedula_tutor' => 'required|boolean',
            'cedula_participante_adulto' => 'nullable|boolean',
            // --- REGLAS AÑADIDAS/MODIFICADAS ---
            'programa' => ['required', 'array', 'min:1'], // Asume que programa principal es un array de checkboxes
            'programas' => ['nullable', 'array'], // El array de checkboxes de subprogramas
            'programas.*' => ['string'], // Cada valor en el array debe ser un string
            'nuevo_subprograma' => ['nullable', 'string', 'max:100'], // El nuevo campo de texto
            'lugar_de_encuentro_del_programa' => ['required', 'string'],
                'nueva_lugar_de_encuentro_del_programa' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
            'primer_nombre_p' => 'required|string|max:255',
            'segundo_nombre_p' => 'nullable|string|max:255',
            'primer_apellido_p' => 'required|string|max:255',
            'segundo_apellido_p' => 'nullable|string|max:255',
            'ciudad_p' => 'nullable|string|max:255',
            'departamento_p' => 'nullable|string|max:255',
            'fecha_de_nacimiento_p' => 'required|date|before_or_equal:today',
            'edad_p' => 'required|integer|min:0',
            'cedula_participante_adulto_str' => 'nullable|string|max:255',
            'genero' => 'required|string|max:255',
            'comunidad_p' => ['required', 'string'],
            'nueva_comunidad_p' => [
                                    'nullable',
                                    'string',
                                    'max:100',
                                    Rule::requiredIf($this->input('comunidad_p') === '_OTRA_')
                                    ],
            'escuela_p' => 'required|string|max:255',
            'comunidad_escuela' => 'nullable|string|max:255',
            'grado_p' => 'required|string|max:255',
            'turno' => 'nullable|string|max:255',
            'repite_grado' => 'nullable|boolean',
            'dias_de_asistencia_al_programa' => 'required|array|min:1',
            'dias_de_asistencia_al_programa.*' => Rule::in(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'tutor_principal' => 'nullable|string|max:255',
            'nombres_y_apellidos_tutor_principal' => 'nullable|string|max:255',
            'numero_de_cedula_tutor' => 'nullable|string|max:16', // Considerar validación de formato de cédula si aplica
            'comunidad_tutor' => ['nullable', 'string'],
             'nueva_comunidad_tutor' => [
            'nullable',
            'string',
            'max:100',
            Rule::requiredIf($this->input('comunidad_tutor') === '_OTRA_')
        ],
            'direccion_tutor' => 'nullable|string|max:255',
            'telefono_tutor' => 'nullable|string|max:20', // Considerar validación de formato de teléfono
            'sector_economico_tutor' => 'nullable|string|max:30',
            'nivel_de_educacion_formal_adquirido_tutor' => 'nullable|string|max:255',
            'expectativas_del_programa_tutor_principal' => 'nullable|string',
            'tutor_secundario' => 'nullable|string|max:255',
            'nombres_y_apellidos_tutor_secundario' => 'nullable|string|max:255',
            'numero_de_cedula_tutor_secundario' => 'nullable|string|max:16',
            'comunidad_tutor_secundario' => 'nullable|string|max:255',
            'telefono_tutor_secundario' => 'nullable|string|max:255',
            'asiste_a_otros_programas' => 'nullable|boolean',
            'otros_programas' => 'nullable|string',
            'dias_asiste_a_otros_programas' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'programa.*.in' => 'El programa principal seleccionado no es válido.',
            'programas.*.in' => 'El sub-programa o código seleccionado no es válido.',
            'dias_de_asistencia_al_programa.*.in' => 'El día de asistencia seleccionado no es válido.',
            'nueva_comunidad_tutor.required_if' => 'Debes especificar el nombre de la nueva comunidad.',

            // Puedes añadir más mensajes personalizados aquí
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Aquí puedes modificar los datos antes de que se validen
        // Por ejemplo, si 'activo' o los campos booleanos no llegan cuando están desmarcados,
        // podrías establecer un valor por defecto. Laravel maneja bien los checkboxes con value="1".
        // Si usas <input type="hidden" name="activo" value="0"> y <input type="checkbox" name="activo" value="1">
        // Laravel interpretará '0' o '1' que el FormRequest casteará a boolean.
    }
}
