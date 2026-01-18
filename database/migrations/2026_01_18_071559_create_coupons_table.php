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
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['percent', 'fixed']);
        $table->unsignedInteger('value');
        $table->boolean('active')->default(true);
        $table->timestamp('expires_at')->nullable();
        $table->unsignedInteger('min_order_cents')->nullable();
        $table->unsignedInteger('max_uses')->nullable();
        $table->unsignedInteger('used_count')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
