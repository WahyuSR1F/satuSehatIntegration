<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KFAUpdateTemp extends Model
{
    use HasFactory;

    protected $table = 'kfa_temp';

    protected $fillable = [
        'jumlah',
        'page',
        'size',
    ];
}
