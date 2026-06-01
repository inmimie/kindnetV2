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
        Schema::table('users', function (Blueprint $table) {
            // Step 1: Personal Details
            $table->string('ic_type')->nullable();
            $table->string('ic_number')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('race')->nullable();
            $table->string('religion')->nullable();
            $table->string('citizen')->nullable();

            // Step 2: Contact Details
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('district')->nullable();
            $table->string('state_nation')->nullable();
            $table->string('phone_home')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ic_type',
                'ic_number',
                'date_of_birth',
                'place_of_birth',
                'marital_status',
                'race',
                'religion',
                'citizen',
                'address_line1',
                'address_line2',
                'address_line3',
                'city',
                'postcode',
                'district',
                'state_nation',
                'phone_home'
            ]);
        });
    }
};
