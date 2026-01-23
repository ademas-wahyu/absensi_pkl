<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        "nama_mentor",
        "email",
        "no_telepon",
        "divisi_id",
        "keahlian",
    ];

    /**
     * Get the divisi that owns the mentor.
     */
    public function divisi()
    {
        return $this->belongsTo(DivisiAdmin::class, "divisi_id");
    }
}
