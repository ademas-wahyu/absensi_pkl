<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsentUser extends Model
{
    protected $fillable = [
        'user_id',
        'absent_date',
        'status',
        'reason',
    ];

    /**
     * Get the user that owns the absent record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
