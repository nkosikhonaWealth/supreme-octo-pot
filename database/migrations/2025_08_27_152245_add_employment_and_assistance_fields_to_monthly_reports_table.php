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
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->integer('people_hired_seasonal')->default(0)->after('amount_saved');
            $table->integer('people_hired_temporal')->default(0);
            $table->integer('people_hired_full_time')->default(0);
            $table->enum('received_financial_assistance', ['yes', 'no'])->nullable();
            $table->enum('assistance_type', ['grant', 'loan'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            //
        });
    }
};
