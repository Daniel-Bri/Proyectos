<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpWhitelist extends Model
{
    use HasFactory;
    
    protected $table = 'ip_whitelist';
    protected $fillable = [
        'ip_cidr',
        'id_users'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}