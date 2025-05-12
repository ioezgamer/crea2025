<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id('participante_id');
            $table->date('fecha_de_inscripcion');
            $table->year('ano_de_inscripcion');
            $table->string('participante');
            $table->boolean('activo')->default(true);
            $table->boolean('partida_de_nacimiento')->nullable();
            $table->boolean('boletin_o_diploma_2024')->nullable();
            $table->boolean('cedula_tutor')->nullable();
            $table->boolean('cedula_participante_adulto')->nullable();
            $table->string('programas');
            $table->string('lugar_de_encuentro_del_programa');
            $table->string('primer_nombre_p');
            $table->string('segundo_nombre_p')->nullable();
            $table->string('primer_apellido_p');
            $table->string('segundo_apellido_p')->nullable();
            $table->string('ciudad_p')->nullable();
            $table->string('departamento_p')->nullable();
            $table->date('fecha_de_nacimiento_p');
            $table->integer('edad_p');
            $table->string('cedula_participante_adulto_str')->nullable();
            $table->string('genero');
            $table->string('comunidad_p');
            $table->string('escuela_p');
            $table->string('comunidad_escuela');
            $table->string('grado_p');
            $table->string('turno')->nullable();
            $table->boolean('repite_grado')->nullable();
            $table->string('dias_de_asistencia_al_programa');
            $table->string('programa');
            $table->string('tutor_principal');
            $table->string('nombres_y_apellidos_tutor_principal');
            $table->string('numero_de_cedula_tutor')->nullable();
            $table->string('comunidad_tutor')->nullable();
            $table->string('direccion_tutor')->nullable();
            $table->string('telefono_tutor')->nullable();
            $table->string('sector_economico_tutor')->nullable();
            $table->string('nivel_de_educacion_formal_adquirido_tutor')->nullable();
            $table->text('expectativas_del_programa_tutor_principal')->nullable();
            $table->string('tutor_secundario')->nullable();
            $table->string('nombres_y_apellidos_tutor_secundario')->nullable();
            $table->string('numero_de_cedula_tutor_secundario')->nullable();
            $table->string('comunidad_tutor_secundario')->nullable();
            $table->string('telefono_tutor_secundario')->nullable();
            $table->boolean('asiste_a_otros_programas')->nullable();
            $table->string('otros_programas')->nullable();
            $table->string('dias_asiste_a_otros_programas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participantes');
    }
};