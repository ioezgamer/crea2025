<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;
    protected $table = "participantes";
    protected $primaryKey = 'participante_id';

    protected $fillable = [
        'fecha_de_inscripcion',
        'ano_de_inscripcion',
        'participante',
        'partida_de_nacimiento',
        'boletin_o_diploma_2024',
        'cedula_tutor',
        'cedula_participante_adulto',
        'programas',
        'lugar_de_encuentro_del_programa',
        'primer_nombre_p',
        'segundo_nombre_p',
        'primer_apellido_p',
        'segundo_apellido_p',
        'ciudad_p',
        'departamento_p',
        'fecha_de_nacimiento_p',
        'edad_p',
        'cedula_participante_adulto_str',
        'genero',
        'comunidad_p',
        'escuela_p',
        'comunidad_escuela',
        'grado_p',
        'turno',
        'repite_grado',
        'dias_de_asistencia_al_programa',
        'programa',
        'tutor_principal',
        'nombres_y_apellidos_tutor_principal',
        'numero_de_cedula_tutor',
        'comunidad_tutor',
        'direccion_tutor',
        'telefono_tutor',
        'sector_economico_tutor',
        'nivel_de_educacion_formal_adquirido_tutor',
        'expectativas_del_programa_tutor_principal',
        'tutor_secundario',
        'nombres_y_apellidos_tutor_secundario',
        'numero_de_cedula_tutor_secundario',
        'comunidad_tutor_secundario',
        'telefono_tutor_secundario',
        'asiste_a_otros_programas', 
        'otros_programas',
        'activo',
        'dias_asiste_a_otros_programas',
    ];

    protected $casts = [
        
    ];
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'participante_id', 'participante_id');
    }
}