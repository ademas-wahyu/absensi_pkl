<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Menambahkan kolom untuk foto selfie dan lokasi GPS
     */
    public function up(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->string('selfie_path')->nullable()->after('reason');
            $table->decimal('latitude', 10, 8)->nullable()->after('selfie_path');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('verification_method')->default('qr_code')->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->dropColumn(['selfie_path', 'latitude', 'longitude', 'verification_method']);
        });
    }
};
