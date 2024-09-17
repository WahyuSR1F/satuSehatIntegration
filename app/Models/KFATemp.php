<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KFATemp extends Model
{
    use HasFactory;
    protected $table = 'kfa_temp_backup';

    protected $fillable = [
        'jumlah',
        'page',
        'size',
    ];
}
