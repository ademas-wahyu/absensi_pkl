<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAdmin extends Model
{
     protected $fillable = [
        'user_id',
        'name',
        'asal',
    ];
}
