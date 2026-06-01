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
            $table->string('doc_student_ic')->nullable();
            $table->string('doc_student_birth_cert')->nullable();
            $table->string('doc_mother_ic')->nullable();
            $table->string('doc_father_ic')->nullable();
            $table->string('doc_offer_letter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'doc_student_ic',
                'doc_student_birth_cert',
                'doc_mother_ic',
                'doc_father_ic',
                'doc_offer_letter'
            ]);
        });
    }
};
