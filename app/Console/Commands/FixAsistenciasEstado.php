<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asistencia;

class FixAsistenciasEstado extends Command
{
    protected $signature = 'asistencias:fix-estado';

    protected $description = 'Fix invalid estado values in asistencias table';

    public function handle()
    {
        $this->info('Iniciando corrección de estados en la tabla asistencias...');

        // Buscar registros con estado inválido
        $asistencias = Asistencia::whereNotIn('estado', ['Presente', 'Ausente', 'Justificado'])->get();

        if ($asistencias->isEmpty()) {
            $this->info('No se encontraron asistencias con estados inválidos.');
            return 0;
        }

        $count = 0;
        foreach ($asistencias as $asistencia) {
            $asistencia->estado = 'Presente'; // Cambiar a 'Presente' (ajusta según lo que desees)
            $asistencia->save();
            $count++;
            $this->line("Asistencia ID: {$asistencia->id}, Participante ID: {$asistencia->participante_id}, Estado corregido a: {$asistencia->estado}");
        }

        $this->info("Se corrigieron {$count} registros.");
        return 0;
    }
}