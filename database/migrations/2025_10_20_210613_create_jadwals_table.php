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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId("surat_tugas_mengajar_id")->constrained("surat_tugas_mengajar")->onDelete("cascade");
            $table->foreignId("ruangan_id")->constrained("ruangan")->onDelete("cascade");
            $table->foreignId("shift_id")->constrained("shift")->onDelete("cascade");
            $table->enum("hari",["SENIN","SELASA","RABU","KAMIS","JUMAT","SABTU","MINGGU"]);
            $table->enum("status",["AKTIF","NONAKTIF"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
