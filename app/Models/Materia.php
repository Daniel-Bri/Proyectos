<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;
    
    protected $table = 'materia';
    protected $primaryKey = 'sigla';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sigla',
        'nombre',
        'semestre',
        'id_categoria'
    ];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relación con Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }

    // Relación con Docentes (N:M)
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_materia', 'sigla_materia', 'codigo_docente');
    }

    // Relación con GrupoMateria
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class, 'sigla_materia', 'sigla');
    }
}