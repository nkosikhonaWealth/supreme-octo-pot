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
        Schema::create('internal_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('venue');
            $table->date('activity_date');
            $table->time('start_time');
            $table->time('finish_time');
            $table->string('data_collector');
            $table->string('verified_by');
            $table->date('collection_date');
            $table->date('verification_date');


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_attendances');
    }
};
