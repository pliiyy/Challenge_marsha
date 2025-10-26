<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kosma extends Model
{
    protected $table = 'kosma';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Kelas()
    {
        return $this->hasOne(Kelas::class);
    }
}
