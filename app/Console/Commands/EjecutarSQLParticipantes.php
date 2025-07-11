<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EjecutarSQLParticipantes extends Command
{
    protected $signature = 'sql:participantes';
    protected $description = 'Ejecuta el archivo SQL con datos de participantes';

    public function handle()
    {
        $path = database_path('scripts/participantes.sql');

        if (!file_exists($path)) {
            $this->error('El archivo participantes.sql no se encontró.');
            return 1;
        }

        $sql = file_get_contents($path);

        try {
            DB::unprepared($sql);
            $this->info('Datos insertados correctamente desde el archivo SQL.');
        } catch (\Exception $e) {
            $this->error('Error al ejecutar el SQL: ' . $e->getMessage());
        }

        return 0;
    }
}

