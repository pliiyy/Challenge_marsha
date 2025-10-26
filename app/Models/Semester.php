<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semester';
    protected $guarded = ['id'];
    
    public function MataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function Semester()
    {
        return $this->hasMany(Dekan::class);
    }
}
