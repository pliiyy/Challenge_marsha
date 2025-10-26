<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
     protected $guarded = ['id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function Prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
