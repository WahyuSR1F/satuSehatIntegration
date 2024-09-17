<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenAccess extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    // Set jenis key type
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'client_id',
        'token',
        'issued_at',
        'aplication_name',
        'interval_access'
    ];
}