<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
     protected $guarded = ['id'];

    public function SuratTugasMengajar()
    {
        return $this->belongsTo(SuratTugasMengajar::class);
    }
    public function Ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
    public function Shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
