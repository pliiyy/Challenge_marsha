<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'user';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function Biodata()
    {
        return $this->hasOne(Biodata::class);
    }
    public function Dekan()
    {
        return $this->hasOne(Dekan::class);
    }
    public function Dosen()
    {
        return $this->hasOne(Dosen::class);
    }
    public function Kaprodi()
    {
        return $this->hasOne(Kaprodi::class);
    }
    public function Sekprodi()
    {
        return $this->hasOne(Sekprodi::class);
    }
    public function Kosma()
    {
        return $this->hasOne(Kosma::class);
    }
    public function Mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }
}
