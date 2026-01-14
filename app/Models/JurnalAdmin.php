<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalAdmin extends Model
{
    protected $fillable = [
        'user_id',
        'jurnal_date',
        'activity',
    ];
}
