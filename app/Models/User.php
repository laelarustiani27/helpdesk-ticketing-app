<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'id';        
    public $incrementing = true;        
    protected $keyType = 'int';  

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'tanggal_lahir',
        'email',
        'no_telepon',
        'is_active',
        'last_login',
        'notif_enabled',
        'notif_ticket',
        'notif_assign',
        'language',
        'theme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'      => 'hashed',
        'tanggal_lahir' => 'date',
        'last_login'    => 'datetime',
        'is_active'     => 'boolean',
        'notif_enabled' => 'boolean',
        'notif_ticket'  => 'boolean',
        'notif_assign'  => 'boolean',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getTanggalLahirFormatAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d-m-Y') : null;
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeTeknisi($query)
    {
        return $query->where('role', 'teknisi');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }
}