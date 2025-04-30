<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciasTable extends Migration
{
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            // Clave foránea correcta según tu modelo
            $table->unsignedBigInteger('participante_id');

            $table->foreign('participante_id')
                  ->references('participante_id')  // <- Apunta a la PK 'participante_id' de 'participantes'
                  ->on('participantes')
                  ->onDelete('cascade'); // Borra asistencias si se borra el participante

            $table->date('fecha_asistencia');
            $table->enum('estado', ['Presente', 'Ausente', 'Justificado'])->default('Ausente');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
}
