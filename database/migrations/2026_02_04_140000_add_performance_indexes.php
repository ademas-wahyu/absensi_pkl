<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Menambahkan index untuk meningkatkan performa query
     */
    public function up(): void
    {
        // Index untuk tabel absent_users
        Schema::table('absent_users', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('absent_date');
        });

        // Index untuk tabel jurnal_users
        Schema::table('jurnal_users', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('jurnal_date');
        });

        // Index untuk tabel users (kolom yang sering di-filter)
        Schema::table('users', function (Blueprint $table) {
            $table->index('divisi');
            $table->index('sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['absent_date']);
        });

        Schema::table('jurnal_users', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['jurnal_date']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['divisi']);
            $table->dropIndex(['sekolah']);
        });
    }
};
