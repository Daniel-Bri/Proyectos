<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categoria';
    protected $fillable = [
        'nombre'
    ];

    // Relación con Materias
    public function materias()
    {
        return $this->hasMany(Materia::class, 'id_categoria');
    }
}