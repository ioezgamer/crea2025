<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckAsistenciasSchema extends Command
{
    protected $signature = 'asistencias:check-schema';

    protected $description = 'Check the schema of the asistencias table';

    public function handle()
    {
        $this->info('Inspeccionando el esquema de la tabla asistencias...');

        $columns = DB::select("SHOW COLUMNS FROM asistencias LIKE 'estado'");

        if (empty($columns)) {
            $this->error('No se encontró el campo estado en la tabla asistencias.');
            return 1;
        }

        $this->info('Detalles del campo estado:');
        $this->line("Tipo: {$columns[0]->Type}");
        $this->line("Nulo: {$columns[0]->Null}");
        $this->line("Valor por defecto: " . ($columns[0]->Default ?? 'Ninguno'));

        return 0;
    }
}