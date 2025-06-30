<?php

namespace App\Http\Requests\Asistencia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIndividualRequest extends FormRequest
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
        return [
            'participante_id' => 'required|integer|exists:participantes,participante_id',
            'fecha_asistencia' => 'required|date_format:Y-m-d',
            'estado' => ['required', Rule::in(['Presente', 'Ausente', 'Justificado'])],
        ];
    }
}
