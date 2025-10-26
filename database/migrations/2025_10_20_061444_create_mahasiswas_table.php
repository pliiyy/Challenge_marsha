<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string("nim")->unique();
            $table->foreignId("user_id")->constrained("user")->onDelete("cascade");
            $table->foreignId("kelas_id")->nullable()->constrained("kelas")->onDelete("set null");
            $table->foreignId("prodi_id")->nullable()->constrained("prodi")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
