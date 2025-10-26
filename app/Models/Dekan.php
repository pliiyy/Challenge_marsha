<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dekan extends Model
{
    protected $table = 'dekan';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Fakultas()
    {
        return $this->hasMany(Fakultas::class);
    }
}
