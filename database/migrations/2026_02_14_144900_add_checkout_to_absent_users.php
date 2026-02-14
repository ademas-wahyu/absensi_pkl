<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->timestamp('checkout_at')->nullable()->after('verification_method');
            $table->text('early_leave_reason')->nullable()->after('checkout_at');
        });
    }

    public function down(): void
    {
        Schema::table('absent_users', function (Blueprint $table) {
            $table->dropColumn(['checkout_at', 'early_leave_reason']);
        });
    }
};
