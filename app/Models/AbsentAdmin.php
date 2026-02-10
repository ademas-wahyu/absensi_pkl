<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
