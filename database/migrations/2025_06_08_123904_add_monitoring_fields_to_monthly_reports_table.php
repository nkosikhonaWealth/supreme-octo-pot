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
            $table->string('toolkit_usage_status')->nullable();
            $table->text('toolkit_condition')->nullable();
            $table->unsignedInteger('clients_served')->nullable();
            $table->unsignedInteger('jobs_completed')->nullable();
            $table->unsignedInteger('people_hired')->nullable(); // Optional field
            $table->decimal('amount_saved', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropColumn([
                'toolkit_usage_status',
                'toolkit_condition',
                'clients_served',
                'jobs_completed',
                'people_hired',
                'amount_saved',
            ]);
        });
    }
};
