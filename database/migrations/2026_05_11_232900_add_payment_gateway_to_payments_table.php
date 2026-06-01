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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_gateway')->after('status')->default('bank_transfer');
            $table->string('payment_method')->after('payment_gateway')->nullable();
            $table->string('gateway_reference')->after('payment_method')->nullable();
            $table->text('notes')->after('gateway_reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_gateway', 'payment_method', 'gateway_reference', 'notes']);
        });
    }
};
