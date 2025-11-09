<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateriaHorario extends Model
{
    use HasFactory;

    protected $table = 'grupo_materia_horario';
    
    protected $fillable = [
        'estado_aula',
        'id_grupo_materia',
        'id_horario', 
        'id_aula',
        'id_docente'
    ];

    // Relación con GrupoMateria
    public function grupoMateria()
    {
        return $this->belongsTo(GrupoMateria::class, 'id_grupo_materia');
    }

    // Relación con Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'id_horario');
    }

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    // Relación con Docente (VERSIÓN CORREGIDA - usa 'id_docente')
    public function docente()
    {
        return $this->belongsTo(Docente::class,  'id_docente', 'codigo');
    }

    // Relación con Asistencias (de Daniel)
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_grupo_materia_horario');
    }

    // Scope para horarios activos (de Daniel)
    public function scopeActivos($query)
    {
        return $query->where('estado_aula', 'ocupado');
    }

    // Scope para docente específico (de Daniel)
    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('id_docente', $docenteId);
    }
}