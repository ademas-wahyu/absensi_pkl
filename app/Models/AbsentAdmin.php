<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsentUser extends Model
{
    protected $fillable = [
        'user_id',
        'absent_date',
        'status',
        'reason',
    ];
}
