<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'fecha_contrato',
        'sueldo',
        'telefono',
        'id_users'
    ];

    protected function fechaContrato(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value),
            set: fn ($value) => $value,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function carreras()
    {
        return $this->belongsToMany(
            Carrera::class, 
            'docente_carrera', 
            'codigo_docente',
            'id_carrera',
            'codigo',
            'id'
        );
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'codigo_docente', 'codigo');
    }

    // NUEVA: Relación con GrupoMateriaHorario
    public function grupoMateriaHorarios()
    {
        return $this->hasMany(GrupoMateriaHorario::class, 'codigo_docente', 'codigo');
    }

    // Relación con GruposMateria a través de horarios
    public function gruposMateria()
    {
        return $this->hasManyThrough(
            GrupoMateria::class,
            GrupoMateriaHorario::class,
            'id_docente', // Foreign key on GrupoMateriaHorario
            'id', // Foreign key on GrupoMateria  
            'codigo', // Local key on Docente
            'id_grupo_materia' // Local key on GrupoMateriaHorario
        );
    }
}