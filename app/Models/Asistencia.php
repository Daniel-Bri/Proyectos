<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table = 'asistencia';
    protected $fillable = [
        'fecha',
        'hora_registro',
        'estado',
        'codigo_docente',
        'id_grupo_materia',
        'id_horario'
    ];

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente');
    }

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
    
}