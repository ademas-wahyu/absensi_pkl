<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AbsentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'absent_date',
        'status',
        'reason',
        'selfie_path',
        'latitude',
        'longitude',
        'verification_method',
        'checkout_at',
        'early_leave_reason',
    ];

    protected function casts(): array
    {
        return [
            'checkout_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the absent record.
     *
     * @return BelongsTo<User,AbsentUser>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
