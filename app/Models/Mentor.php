<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Mentor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mentor newQuery()
 * @method static Mentor|null find($id, $columns = ['*'])
 * @method static Mentor findOrFail($id, $columns = ['*'])
 * @method static Mentor create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Mentor latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Collection|Mentor[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection|Mentor[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Mentor with($relations)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_mentor",
        "email",
        "no_telepon",
        "divisi_id",
        "keahlian",
    ];

    /**
     * Get the divisi that owns the mentor.
     *
     * @return BelongsTo<DivisiAdmin,Mentor>
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(DivisiAdmin::class, "divisi_id");
    }
}
