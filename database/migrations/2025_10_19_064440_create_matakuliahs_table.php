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
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->string("kode");
            $table->string("sks");
            $table->enum("status",["AKTIF","NONAKTIF"]);
            $table->foreignId('prodi_id')->constrained("prodi")->onDelete('cascade');
            $table->foreignId('semester_id')->constrained("semester")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
}; 