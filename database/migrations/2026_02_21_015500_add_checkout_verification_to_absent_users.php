<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->decimal('checkout_latitude', 10, 8)->nullable()->after('early_leave_reason');
            $table->decimal('checkout_longitude', 11, 8)->nullable()->after('checkout_latitude');
            $table->string('checkout_selfie_path')->nullable()->after('checkout_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->dropColumn(['checkout_latitude', 'checkout_longitude', 'checkout_selfie_path']);
        });
    }
};
