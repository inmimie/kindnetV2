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
            $table->string('course_name')->after('mother_income')->nullable();
            $table->string('study_level')->after('course_name')->nullable();
            $table->string('university_name')->after('study_level')->nullable();
            $table->year('start_year')->after('university_name')->nullable();
            $table->year('end_year')->after('start_year')->nullable();
            $table->string('account_number')->after('end_year')->nullable();
            $table->string('bank_name')->after('account_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'course_name',
                'study_level',
                'university_name',
                'start_year',
                'end_year',
                'account_number',
                'bank_name',
            ]);
        });
    }
};
