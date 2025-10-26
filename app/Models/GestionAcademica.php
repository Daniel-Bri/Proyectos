<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionAcademica extends Model
{
    use HasFactory;
    
    protected $table = 'gestion_academica';
    
    protected $fillable = [
        'gestion',
        'periodo',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    // Relación con GrupoMateria
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class, 'id_gestion');
    }
}