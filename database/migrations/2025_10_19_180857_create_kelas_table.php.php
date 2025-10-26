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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->string("kapasitas");
            $table->foreignId("angkatan_id")->constrained("angkatan")->onDelete('cascade');
            $table->foreignId('kosma_id')->nullable()->constrained("kosma")->onDelete('set null');
            $table->enum("status",["AKTIF","NONAKTIF"]);
            $table->enum("tipe",["R","NR"])->default("R");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
