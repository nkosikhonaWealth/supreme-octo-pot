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
        Schema::table('participant_results', function (Blueprint $table) {
            $table->unsignedBigInteger('t_o_t_application_id')->nullable()->after('t_v_e_t_id');
            $table->foreign('t_o_t_application_id')->references('id')->on('t_o_t_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participant_results', function (Blueprint $table) {
            $table->dropForeign(['t_o_t_application_id']);
            $table->dropColumn('t_o_t_application_id');
        });
    }
};
