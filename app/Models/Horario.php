<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $table = 'horario';
    protected $fillable = [
        'dia',
        'hora_inicio',
        'hora_fin'
    ];

    // Relación con GrupoMateriaHorario
    public function grupoMateriaHorarios()
    {
        return $this->hasMany(GrupoMateriaHorario::class, 'id_horario');
    }

    // Relación con Asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_horario');
    }

    // Scope para horarios por día
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia', $dia);
    }
}