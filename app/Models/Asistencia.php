<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    
    protected $fillable = [
        'fecha',
        'hora_registro', 
        'estado',
        'id_grupo_materia_horario'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_registro' => 'datetime'
    ];

    // Nueva relación
    public function grupoMateriaHorario()
    {
        return $this->belongsTo(GrupoMateriaHorario::class, 'id_grupo_materia_horario');
    }

    // Scope para búsquedas por fecha
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    // Scope para estado específico
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }
}