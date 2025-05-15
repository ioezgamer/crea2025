<?php

namespace App\Exports;

use App\Models\Participante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ParticipantesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

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
        // Aquí podrías añadir más lógica de filtrado si es necesario

        return $query->orderBy('primer_apellido_p')->orderBy('primer_nombre_p')->get();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID Participante', // participante_id (PK)
            'Fecha Inscripción', // fecha_de_inscripcion
            'Año Inscripción', // ano_de_inscripcion
            'Tipo Participante', // participante (ej: primaria, secundaria)
            'Tiene Partida Nacimiento (1/0)', // partida_de_nacimiento
            'Tiene Boletín/Diploma 2024 (1/0)', // boletin_o_diploma_2024
            'Tutor Presentó Cédula (1/0)', // cedula_tutor (boolean)
            'Participante Adulto Presentó Cédula (1/0)', // cedula_participante_adulto (boolean)
            'Programa Principal (CSV)', // programa
            'Sub-Programas/Códigos (CSV)', // programas
            'Lugar Encuentro Programa', // lugar_de_encuentro_del_programa
            'Primer Nombre', // primer_nombre_p
            'Segundo Nombre', // segundo_nombre_p
            'Primer Apellido', // primer_apellido_p
            'Segundo Apellido', // segundo_apellido_p
            'Ciudad Nacimiento', // ciudad_p
            'Departamento Nacimiento', // departamento_p
            'Fecha Nacimiento', // fecha_de_nacimiento_p
            'Edad', // edad_p
            'Cédula Participante Adulto (Número)', // cedula_participante_adulto_str
            'Género', // genero
            'Comunidad Residencia Participante', // comunidad_p
            'Escuela', // escuela_p
            'Comunidad Escuela', // comunidad_escuela
            'Grado Escolar', // grado_p
            'Turno Escolar', // turno
            'Repite Grado (1/0)', // repite_grado
            'Días Asistencia Programa (CSV)', // dias_de_asistencia_al_programa
            'Relación Tutor Principal', // tutor_principal (ej: Padre, Madre)
            'Nombres y Apellidos Tutor Principal', // nombres_y_apellidos_tutor_principal
            'Número Cédula Tutor Principal', // numero_de_cedula_tutor
            'Comunidad Tutor Principal', // comunidad_tutor
            'Dirección Tutor Principal', // direccion_tutor
            'Teléfono Tutor Principal', // telefono_tutor
            'Sector Económico Tutor Principal', // sector_economico_tutor
            'Nivel Educación Tutor Principal', // nivel_de_educacion_formal_adquirido_tutor
            'Expectativas Tutor Principal', // expectativas_del_programa_tutor_principal
            'Relación Tutor Secundario', // tutor_secundario
            'Nombres y Apellidos Tutor Secundario', // nombres_y_apellidos_tutor_secundario
            'Número Cédula Tutor Secundario', // numero_de_cedula_tutor_secundario
            'Comunidad Tutor Secundario', // comunidad_tutor_secundario
            'Teléfono Tutor Secundario', // telefono_tutor_secundario
            'Asiste Otros Programas (1/0)', // asiste_a_otros_programas
            'Nombres Otros Programas', // otros_programas
            'Días Asiste Otros Programas', // dias_asiste_a_otros_programas
            'Activo (1/0)', // activo
        ];
    }

    /**
    * @param mixed $participanteModel
    * @return array
    */
    public function map($participanteModel): array
    {
        return [
            $participanteModel->participante_id,
            $participanteModel->fecha_de_inscripcion ? Carbon::parse($participanteModel->fecha_de_inscripcion)->format('Y-m-d') : '',
            $participanteModel->ano_de_inscripcion,
            $participanteModel->participante,
            $participanteModel->partida_de_nacimiento ? 1 : 0,
            $participanteModel->boletin_o_diploma_2024 ? 1 : 0,
            $participanteModel->cedula_tutor ? 1 : 0,
            $participanteModel->cedula_participante_adulto ? 1 : 0,
            $participanteModel->programa,
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
            $participanteModel->repite_grado ? 1 : 0,
            $participanteModel->dias_de_asistencia_al_programa,
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
            $participanteModel->asiste_a_otros_programas ? 1 : 0,
            $participanteModel->otros_programas,
            $participanteModel->dias_asiste_a_otros_programas,
            $participanteModel->activo ? 1 : 0,
        ];
    }
}
