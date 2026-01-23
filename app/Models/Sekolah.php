<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah newQuery()
 * @method static Sekolah|null find($id, $columns = ['*'])
 * @method static Sekolah findOrFail($id, $columns = ['*'])
 * @method static Sekolah create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Sekolah latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Collection|Sekolah[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection|Sekolah[] get($columns = ['*'])
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = ["nama_sekolah", "alamat", "no_telepon"];
}
