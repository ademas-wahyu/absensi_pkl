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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mentor');
            $table->string('email')->nullable();
            $table->string('no_telepon')->nullable();
            $table
                ->foreignId('divisi_id')
                ->constrained('divisi_admins')
                ->onDelete('cascade');
            $table->text('keahlian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};
