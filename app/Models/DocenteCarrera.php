<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteCarrera extends Model
{
    use HasFactory;
    protected $table = 'docente_carrera';

    protected $primaryKey = ['codigo_docente', 'id_carrera'];
    public $incrementing = false;

    protected $fillable = [
        'codigo_docente',
        'id_carrera'
    ];

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente');
    }

    // Relación con Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }
}