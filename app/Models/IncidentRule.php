<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'field',
        'operator',
        'value',
        'issue_type',
        'priority',
        'is_active'
    ];
}