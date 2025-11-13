<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTwoFactor extends Model
{
    use HasFactory;

    protected $table = 'user_two_factor';
    protected $primaryKey = 'id_users';
    public $incrementing = false;

    protected $fillable = [
        'secret',
        'recovery_codes',
        'id_users'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Convertir recovery_codes a array
    public function getRecoveryCodesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRecoveryCodesAttribute($value)
    {
        $this->attributes['recovery_codes'] = json_encode($value);
    }
}