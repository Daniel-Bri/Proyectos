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

    // CORREGIDO: Usar id_docente (confirmado por el modelo GrupoMateriaHorario)
    public function horarios()
    {
        return $this->hasMany(GrupoMateriaHorario::class, 'id_docente', 'codigo');
    }

    // NUEVO: Método corregido para obtener materias asignadas
    public function materiasAsignadas()
    {
        return Materia::whereHas('grupoMaterias.horarios', function($query) {
            $query->where('id_docente', $this->codigo);
        })
        ->with([
            'categoria:id,nombre',
            'grupoMaterias' => function($query) {
                $query->whereHas('horarios', function($q) {
                    $q->where('id_docente', $this->codigo);
                })->with([
                    'grupo',
                    'gestion',
                    'horarios' => function($q) {
                        $q->where('id_docente', $this->codigo)
                          ->with(['horario', 'aula']);
                    }
                ]);
            }
        ])
        ->orderBy('sigla')
        ->get();
    }
}