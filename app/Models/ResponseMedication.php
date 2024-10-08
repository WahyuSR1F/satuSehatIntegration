<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseMedication extends Model
{
    use HasFactory;

    protected $table = 'response_medications';

    protected $fillable = [
        'id_medication',
        'org_id',
        'response',
    ];
}
