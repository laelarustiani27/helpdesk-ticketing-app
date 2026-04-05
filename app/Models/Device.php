<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'nama',
        'ip_address',
        'lokasi',
        'status',
        'last_down_at',
        'last_up_at',
    ];

    protected $casts = [
        'last_down_at' => 'datetime',
        'last_up_at'   => 'datetime',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'nama_tempat', 'nama');
    }

    public function isDown(): bool
    {
        return $this->status === 'down';
    }

    public function isUp(): bool
    {
        return $this->status === 'up';
    }
}