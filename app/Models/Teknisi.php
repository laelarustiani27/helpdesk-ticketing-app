<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = 'teknisi';

    protected $fillable = [
        'name',
        'email',
        'password',   
        'spesialisasi',
        'level',
        'status',
        'last_login',
    ];

    protected $hidden = [
        'password',   
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];
}