<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    // ESPECIFICAR EL NOMBRE EXACTO DE LA TABLA
    protected $table = 'carrera';

    protected $fillable = [
        'nombre'
    ];

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_carrera', 'id_carrera', 'codigo_docente');
    }

    public function materias()
    {
        return $this->hasMany(Materia::class, 'id_carrera');
    }
}