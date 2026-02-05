<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsentAdmin extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'absent_date',
        'divisi',
        'status',
        'reason',
    ];
}
