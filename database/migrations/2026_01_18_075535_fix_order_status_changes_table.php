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
    if (!Schema::hasColumn('order_status_changes', 'order_id')) {
        Schema::table('order_status_changes', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    if (!Schema::hasColumn('order_status_changes', 'old_status')) {
        Schema::table('order_status_changes', function (Blueprint $table) {
            $table->string('old_status')->nullable()->after('order_id');
        });
    }

    if (!Schema::hasColumn('order_status_changes', 'new_status')) {
        Schema::table('order_status_changes', function (Blueprint $table) {
            $table->string('new_status')->after('old_status');
        });
    }

    if (!Schema::hasColumn('order_status_changes', 'changed_by_user_id')) {
        Schema::table('order_status_changes', function (Blueprint $table) {
            $table->foreignId('changed_by_user_id')
                ->nullable()
                ->after('new_status')
                ->constrained('users')
                ->nullOnDelete();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_changes', function (Blueprint $table) {
            //
        });
    }
};
