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
        Schema::create('barter_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId("jadwal_awal_id")->constrained("jadwal")->onDelete("cascade");
            $table->foreignId("jadwal_tukar_id")->constrained("jadwal")->onDelete("cascade");
            $table->text('alasan')->nullable();
            $table->enum('status',["PENDING","APPROVED","REJECTED"])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barter_jadwal');
    }
};
