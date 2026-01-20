<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalUser extends Model
{
    protected $fillable = [
        'user_id',
        'jurnal_date',
        'activity',
    ];

    /**
     * Get the user that owns the jurnal record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
