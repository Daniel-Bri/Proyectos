<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
    protected $table = 'aula';
    protected $fillable = [
        'nombre',
        'capacidad',
        'tipo'
    ];

    // Relación con GrupoMateriaHorario
    public function grupoMateriaHorarios()
    {
        return $this->hasMany(GrupoMateriaHorario::class, 'id_aula');
    }

    // Scope para aulas disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    // Scope para aulas por tipo
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}