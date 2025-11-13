<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;
    
    protected $table = 'auditorias';
    protected $fillable = [
        'accion',
        'entidad',
        'entidad_id',
        'ip',
        'user_agent',
        'id_users'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}