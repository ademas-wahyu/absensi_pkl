<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|DivisiAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|DivisiAdmin newQuery()
 * @method static DivisiAdmin|null find($id, $columns = ['*'])
 * @method static DivisiAdmin findOrFail($id, $columns = ['*'])
 * @method static DivisiAdmin create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DivisiAdmin latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Collection|DivisiAdmin[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection|DivisiAdmin[] get($columns = ['*'])
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DivisiAdmin extends Model
{
    use HasFactory;

    protected $fillable = ["nama_divisi", "deskripsi"];

    /**
     * Get the mentors for the divisi.
     *
     * @return HasMany<Mentor,DivisiAdmin>
     */
    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class, "divisi_id");
    }
}
