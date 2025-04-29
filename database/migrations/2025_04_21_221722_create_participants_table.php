<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->date('fecha_de_inscripcion');
            $table->year('ano_de_inscripcion');
            $table->enum('participante', ['primaria', 'secundaria']);
            $table->boolean('partida_de_nacimiento');
            $table->boolean('boletin_o_diploma_2024');
            $table->boolean('cedula_tutor');
            $table->boolean('cedula_participante_adulto');
            $table->string('programas');
            $table->string('lugar_de_encuentro_del_programa');
            $table->string('primer_nombre_p');
            $table->string('segundo_nombre_p')->nullable();
            $table->string('primer_apellido_p');
            $table->string('segundo_apellido_p')->nullable();
            $table->string('ciudad_p');
            $table->string('departamento_p');
            $table->date('fecha_de_nacimiento_p');
            $table->integer('edad_p');
            $table->string('cedula_participante_adulto_str')->nullable();
            $table->string('genero');
            $table->string('comunidad_p');
            $table->string('escuela_p');
            $table->string('comunidad_escuela');
            $table->string('grado_p');
            $table->string('turno');
            $table->boolean('repite_grado');
            $table->integer('dias_de_asistencia_al_programa');
            $table->string('programa');
            $table->string('tutor_principal');
            $table->string('nombres_y_apellidos_tutor_principal');
            $table->string('numero_de_cedula_tutor');
            $table->string('comunidad_tutor');
            $table->string('direccion_tutor');
            $table->string('telefono_tutor');
            $table->string('sector_economico_tutor');
            $table->string('nivel_de_educacion_formal_adquirido_tutor');
            $table->text('expectativas_del_programa_tutor_principal');
            $table->string('tutor_secundario')->nullable();
            $table->string('nombres_y_apellidos_tutor_secundario')->nullable();
            $table->string('numero_de_cedula_tutor_secundario')->nullable();
            $table->string('comunidad_tutor_secundario')->nullable();
            $table->string('telefono_tutor_secundario')->nullable();
            $table->boolean('asiste_a_otros_programas');
            $table->string('otros_programas')->nullable();
            $table->integer('dias_asiste_a_otros_programas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};