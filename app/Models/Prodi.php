<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $guarded = ['id'];

    public function Fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
    public function MataKuliah()
    {
        return $this->hasMany(Matakuliah::class);
    }
    public function Kaprodi()
    {
        return $this->belongsTo(Kaprodi::class);
    }
    public function Sekprodi()
    {
        return $this->belongsTo(Sekprodi::class);
    }
    public function Mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
    public function Dosen()
    {
        return $this->hasMany(Dosen::class);
    }
}
