<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugasMengajar extends Model
{
    protected $table = 'surat_tugas_mengajar';
    protected $guarded = ['id'];

    public function Dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
    public function Matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }
    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function Jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}
