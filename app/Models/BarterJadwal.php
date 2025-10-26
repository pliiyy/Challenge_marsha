<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarterJadwal extends Model
{
    protected $table = 'barter_jadwal';
    protected $guarded = ['id'];

    public function JadwalAwal()
    {
        return $this->belongsTo(Jadwal::class,"jadwal_awal_id");
    }
    public function JadwalTukar()
    {
        return $this->belongsTo(Jadwal::class,"jadwal_tukar_id");
    }
}
