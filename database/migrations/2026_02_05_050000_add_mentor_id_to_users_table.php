<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom mentor_id untuk relasi dengan mentor
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('mentor_id')->nullable()->after('sekolah');
            $table->foreign('mentor_id')->references('id')->on('mentors')->onDelete('set null');
            $table->index('mentor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->dropIndex(['mentor_id']);
            $table->dropColumn('mentor_id');
        });
    }
};
