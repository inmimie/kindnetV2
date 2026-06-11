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
        Schema::table('applications', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('status');
        });

        // Set approved_at for existing approved applications
        \Illuminate\Support\Facades\DB::table('applications')
            ->where('status', 'approved')
            ->update(['approved_at' => \Illuminate\Support\Facades\DB::raw('updated_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
