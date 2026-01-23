<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisiAdmin extends Model
{
    protected $fillable = ["nama_divisi", "deskripsi"];

    /**
     * Get the mentors for the divisi.
     */
    public function mentors()
    {
        return $this->hasMany(Mentor::class, "divisi_id");
    }
}
