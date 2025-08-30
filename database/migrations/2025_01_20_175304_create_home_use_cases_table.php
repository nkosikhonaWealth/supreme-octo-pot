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
        Schema::create('home_use_cases', function (Blueprint $table) {
            $table->id();

            $table->string('home_use_case_title');
            $table->string('home_use_case_problem_title');
            $table->string('home_use_case_problem_text');
            $table->string('home_use_case_solution_title');
            $table->string('home_use_case_solution_text');
            $table->string('home_use_case_outcome_title');
            $table->string('home_use_case_outcome_text');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_use_cases');
    }
};
