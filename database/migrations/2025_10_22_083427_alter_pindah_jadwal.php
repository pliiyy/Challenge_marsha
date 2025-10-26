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
        Schema::table("pindah_jadwal",function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum("status",["AKTIF","NONAKTIF",'PENDING',"APPROVED","REJECTED"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("pindah_jadwal",function (Blueprint $table) {
            $table->enum("status",["AKTIF","NONAKTIF",'PENDING',"APPROVED","REJECTED"]);
        });
    }
};
