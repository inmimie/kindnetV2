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
            // Tab 1: Applicant Info
            $table->string('applicant_name')->nullable();
            $table->string('applicant_ic')->nullable();
            $table->date('applicant_dob')->nullable();
            $table->string('applicant_gender')->nullable();
            $table->string('applicant_marital_status')->nullable();
            $table->text('applicant_address')->nullable();
            $table->string('applicant_phone')->nullable();
            $table->string('applicant_occupation')->nullable();
            $table->string('applicant_email')->nullable();

            // Tab 2: Father/Mother/Guardian Info
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->decimal('father_income', 10, 2)->nullable();
            
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->decimal('mother_income', 10, 2)->nullable();
            
            $table->integer('total_dependents')->nullable();
            $table->decimal('total_income', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_name', 'applicant_ic', 'applicant_dob', 'applicant_gender', 'applicant_marital_status',
                'applicant_address', 'applicant_phone', 'applicant_occupation', 'applicant_email',
                'father_name', 'father_occupation', 'father_income', 
                'mother_name', 'mother_occupation', 'mother_income',
                'total_dependents', 'total_income'
            ]);
        });
    }
};
