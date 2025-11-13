<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'grupo';
    protected $fillable = [
        'nombre',
        'gestion'
    ];

    // Relación con Materias (N:M)
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'grupo_materia', 'id_grupo', 'sigla_materia')
                    ->withPivot('id_gestion')
                    ->withTimestamps();
    }

    // Relación con GrupoMateria
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class, 'id_grupo');
    }
}