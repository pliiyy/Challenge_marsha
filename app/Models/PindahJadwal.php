<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PindahJadwal extends Model
{
    protected $table = 'pindah_jadwal';
    protected $guarded = ['id'];
    
    public function Jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
    public function Shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function Ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
