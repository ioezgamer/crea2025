<?php

namespace App\Http\Requests\Asistencia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GetFilteredDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Asumimos que si el usuario puede acceder a la ruta, está autorizado.
        // Puedes añadir lógica de autorización más compleja aquí si es necesario.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'programa' => 'required|string|max:255',
            'lugar_de_encuentro_del_programa' => 'required|string|max:255',
            'grado_p' => 'required|string|max:255',
            'fecha' => ['required', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
                if ($this->input('tipo_asistencia') === 'semanal') {
                    if (Carbon::parse($value)->dayOfWeek !== Carbon::MONDAY) {
                        $fail('Para asistencia semanal, la ' . $attribute . ' debe ser un lunes.');
                    }
                }
            }],
            'tipo_asistencia' => ['required', Rule::in(['semanal', 'diaria'])],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'programa' => 'programa',
            'lugar_de_encuentro_del_programa' => 'lugar de encuentro',
            'grado_p' => 'grado',
            'fecha' => 'fecha',
            'tipo_asistencia' => 'tipo de asistencia',
        ];
    }
}
