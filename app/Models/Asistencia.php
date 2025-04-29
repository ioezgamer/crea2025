<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    protected $primaryKey = 'asistencia_id';

    protected $fillable = [
        'participante_id',
        'fecha_asistencia',
        'estado',
    ];
    protected $casts = [
        'estado' => 'integer', // Asegura que estado sea tratado como entero
        'fecha_asistencia' => 'date',
    ];
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'participante_id');
    }
}