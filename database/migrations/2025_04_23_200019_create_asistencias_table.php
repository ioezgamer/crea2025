<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciasTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('asistencias')) {
            Schema::create('asistencias', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('participant_id');
                $table->date('fecha_asistencia');
                $table->enum('estado', ['Presente', 'Ausente', 'Justificado'])->default('Ausente');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
}