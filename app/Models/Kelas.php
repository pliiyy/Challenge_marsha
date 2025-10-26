<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = ['id'];

    public function Angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function Kosma()
    {
        return $this->belongsTo(Kosma::class);
    }
    public function Mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
