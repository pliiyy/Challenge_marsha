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
        Schema::create('pindah_jadwal', function (Blueprint $table) {
            $table->id();
            $table->text('alasan')->nullable();
            $table->enum("hari",["SENIN","SELASA","RABU","KAMIS","JUMAT","SABTU","MINGGU"]);
            $table->foreignId("jadwal_id")->constrained("jadwal")->onDelete("cascade");
            $table->foreignId("ruangan_id")->nullable()->constrained("ruangan")->onDelete("set null");
            $table->foreignId("shift_id")->nullable()->constrained("shift")->onDelete("set null");
            $table->foreignId("kosma_id")->nullable()->nullable()->constrained("kosma")->onDelete("set null");
            $table->enum("status",["AKTIF","NONAKTIF","APPROVED","REJECTED"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pindah_jadwal');
    }
};
