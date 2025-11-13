<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    // CORREGIDO: La tabla se llama 'asistencia' en singular
    protected $table = 'asistencia';
    
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

    // Relación con GrupoMateriaHorario
    public function grupoMateriaHorario()
    {
        return $this->belongsTo(GrupoMateriaHorario::class, 'id_grupo_materia_horario');
    }

    // Relación indirecta con GrupoMateria a través de GrupoMateriaHorario
    public function grupoMateria()
    {
        return $this->hasOneThrough(
            GrupoMateria::class,
            GrupoMateriaHorario::class,
            'id', // Foreign key on GrupoMateriaHorario table
            'id', // Foreign key on GrupoMateria table  
            'id_grupo_materia_horario', // Local key on Asistencia table
            'id_grupo_materia' // Local key on GrupoMateriaHorario table
        );
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