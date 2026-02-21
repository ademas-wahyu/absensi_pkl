<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jurnal_users', function (Blueprint $table) {
            $table->boolean('is_pending_edit')->default(false);
            $table->date('pending_jurnal_date')->nullable();
            $table->text('pending_activity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_users', function (Blueprint $table) {
            $table->dropColumn(['is_pending_edit', 'pending_jurnal_date', 'pending_activity']);
        });
    }
};
