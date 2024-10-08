<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationRequest extends Model
{
    use HasFactory;

    protected $table = 'medication_requests';

    protected $fillable = [
        'id_medication_request',
        'response',
    ];


}
