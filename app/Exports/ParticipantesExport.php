<?php

namespace App\Exports;

use App\Models\Participante;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantesExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Participante::query();

        if (!empty($this->filters['search_name'])) {
            $query->filterByName($this->filters['search_name']);
        }

        if (!empty($this->filters['search_programa'])) {
            $query->filterByPrograma($this->filters['search_programa']);
        }

        if (!empty($this->filters['search_lugar'])) {
            $query->filterByLugar($this->filters['search_lugar']);
        }

        if (!empty($this->filters['grado'])) {
            $query->filterByGrado(urldecode($this->filters['grado']));
        }

        return $query->orderBy('primer_apellido_p')
                     ->orderBy('primer_nombre_p')
                     ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Participante_ID',
            'Fecha de inscripción',
            'Participante',
            'Partida de nacimiento',
            'Boletín o diploma (2024)',
            'Cédula tutor',
            'Cédula participante adulto',
            'Programas',
            'Lugar de encuentro del programa',
            'Primer nombre (P)',
            'Segundo nombre (P)',
            'Primer apellido (P)',
            'Segundo apellido (P)',
            'Ciudad (P)',
            'Departamento (P)',
            'Fecha de nacimiento (P)',
            'Edad (P)',
            'Cédula (Participante Adulto)',
            'Genero',
            'Comunidad (P)',
            'Escuela (P)',
            'Comunidad (Escuela)',
            'Grado (P)',
            'Turno',
            '¿Repite grado?',
            'Días de asistencia al programa',
            'Programa',
            'Tutor principal',
            'Nombres y apellidos (Tutor principal)',
            'Número de cédula (Tutor)',
            'Comunidad (Tutor)',
            'Dirección (Tutor)',
            'Teléfono (Tutor)',
            'Sector económico (Tutor)',
            'Nivel de educación formal adquirido (Tutor)',
            '¿Cuáles son sus expectativas del programa? (Tutor principal)',
            'Tutor secundario',
            'Nombres y apellidos (Tutor secundario)',
            'Número de cédula (Tutor secundario)',
            'Comunidad (Tutor secundario)',
            'Teléfono (Tutor secundario)',
            '¿El participante asiste a otros programas de la comunidad?',
            'Otros programas a los que asiste el participante',
            'Días que asiste a otros programas',
            'Año de inscripción',
            'Activo',
        ];
    }

    /**
     * @param \App\Models\Participante $participanteModel
     * @return array
     */
    public function map($participanteModel): array
    {
        $boolToText = fn($value) => $value ? 'Sí' : 'No';

        return [
            $participanteModel->participante_id,
            $participanteModel->fecha_de_inscripcion ? Carbon::parse($participanteModel->fecha_de_inscripcion)->format('Y-m-d') : '',
            $participanteModel->participante,
            $boolToText($participanteModel->partida_de_nacimiento),
            $boolToText($participanteModel->boletin_o_diploma_2024),
            $boolToText($participanteModel->cedula_tutor),
            $boolToText($participanteModel->cedula_participante_adulto),
            $participanteModel->programas,
            $participanteModel->lugar_de_encuentro_del_programa,
            $participanteModel->primer_nombre_p,
            $participanteModel->segundo_nombre_p,
            $participanteModel->primer_apellido_p,
            $participanteModel->segundo_apellido_p,
            $participanteModel->ciudad_p,
            $participanteModel->departamento_p,
            $participanteModel->fecha_de_nacimiento_p ? Carbon::parse($participanteModel->fecha_de_nacimiento_p)->format('Y-m-d') : '',
            $participanteModel->edad_p,
            $participanteModel->cedula_participante_adulto_str,
            $participanteModel->genero,
            $participanteModel->comunidad_p,
            $participanteModel->escuela_p,
            $participanteModel->comunidad_escuela,
            $participanteModel->grado_p,
            $participanteModel->turno,
            $boolToText($participanteModel->repite_grado),
            $participanteModel->dias_de_asistencia_al_programa,
            $participanteModel->programa,
            $participanteModel->tutor_principal,
            $participanteModel->nombres_y_apellidos_tutor_principal,
            $participanteModel->numero_de_cedula_tutor,
            $participanteModel->comunidad_tutor,
            $participanteModel->direccion_tutor,
            $participanteModel->telefono_tutor,
            $participanteModel->sector_economico_tutor,
            $participanteModel->nivel_de_educacion_formal_adquirido_tutor,
            $participanteModel->expectativas_del_programa_tutor_principal,
            $participanteModel->tutor_secundario,
            $participanteModel->nombres_y_apellidos_tutor_secundario,
            $participanteModel->numero_de_cedula_tutor_secundario,
            $participanteModel->comunidad_tutor_secundario,
            $participanteModel->telefono_tutor_secundario,
            $boolToText($participanteModel->asiste_a_otros_programas),
            $participanteModel->otros_programas,
            $participanteModel->dias_asiste_a_otros_programas,
            $participanteModel->ano_de_inscripcion,
            $boolToText($participanteModel->activo),
        ];
    }
}
