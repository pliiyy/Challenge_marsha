<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';
    protected $guarded = ['id'];

    public function Prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    public function Semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function SuratTugasMengajar()
    {
        return $this->hasMany(SuratTugasMengajar::class, 'matakuliah_id');
    }
}
