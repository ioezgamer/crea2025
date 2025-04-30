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
        
    ];
    public function participante()
    {
        return $this->belongsTo(Participante::class, 'participante_id', 'participante_id');
    }
}