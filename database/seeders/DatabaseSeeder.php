<?php

namespace Database\Seeders;

use App\Models\Biodata;
use App\Models\Dekan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $user = User::create([
            'username' => 'master',
            'email' => 'master@gmail.com',
            'no_telepon' => '001001001',
            'password' => Hash::make('master01'),
        ]);
        Biodata::create([
            "user_id" => $user->id,
            'nama' => 'Master',
        ]);
        Dekan::create([
            "user_id" => $user->id,
            'nidn' => 'N01192',
        ]);

    }
}
