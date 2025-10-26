<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $guarded = ['id'];

    public function Prodi()
    {
        return $this->hasMany(Prodi::class);
    }
    public function Dekan()
    {
        return $this->belongsTo(Dekan::class);
    }
}
