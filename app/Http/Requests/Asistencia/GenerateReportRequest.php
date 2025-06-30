<?php

namespace App\Http\Requests\Asistencia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GenerateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Hacemos que lugar y grado sean opcionales para el reporte
        return [
            'programa' => 'required|string|max:255',
            'lugar_de_encuentro_del_programa' => 'nullable|string|max:255',
            'grado_p' => 'nullable|string|max:255',
            'fecha' => ['required', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
                if ($this->input('tipo_asistencia') === 'semanal') {
                    if (Carbon::parse($value)->dayOfWeek !== Carbon::MONDAY) {
                        $fail('Para un reporte semanal, la ' . $attribute . ' de inicio debe ser un lunes.');
                    }
                }
            }],
            'tipo_asistencia' => ['required', Rule::in(['semanal', 'diaria'])],
        ];
    }
}
