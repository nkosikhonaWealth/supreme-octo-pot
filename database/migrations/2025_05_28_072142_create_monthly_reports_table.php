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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained();
            $table->date('report_month')->unique();
            $table->text('activities_performed');
            $table->text('challenges_faced');
            $table->json('proof_of_work');  
            $table->decimal('income_generated', 10, 2)->nullable();
            $table->boolean('admin_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
