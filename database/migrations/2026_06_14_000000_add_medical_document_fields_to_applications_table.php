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
            $table->string('doc_salary_slip')->nullable();
            $table->string('doc_marriage_cert')->nullable();
            $table->string('doc_medical_report')->nullable();
            $table->string('doc_pharmacy_quote')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'doc_salary_slip',
                'doc_marriage_cert',
                'doc_medical_report',
                'doc_pharmacy_quote'
            ]);
        });
    }
};
