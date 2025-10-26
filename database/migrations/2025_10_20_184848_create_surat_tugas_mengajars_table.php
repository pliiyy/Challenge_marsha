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
        Schema::create('surat_tugas_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId("dosen_id")->constrained("dosen")->onDelete("cascade");
            $table->foreignId("matakuliah_id")->constrained("matakuliah")->onDelete("cascade");
            $table->foreignId("kelas_id")->constrained("kelas")->onDelete("cascade");
            $table->enum('status',['PENDING','APPROVED',"REJECTED"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_mengajar');
    }
};
