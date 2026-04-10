<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'foto',
        'status',
        'priority',
        'location',
        'reported_by',
        'assigned_to',
        'resolved_at',
        'reported_at',
        'nama_tempat',
        'alamat',
        'koordinat',
        'no_telepon',
        'pelanggan_id',  
        'email',
        'jenis_pemesanan',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at'  => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}