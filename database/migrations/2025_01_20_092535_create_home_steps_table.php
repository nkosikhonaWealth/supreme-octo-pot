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
        Schema::create('home_steps', function (Blueprint $table) {
            $table->id();

            $table->string('home_steps_1_title');
            $table->string('home_steps_1_text');
            $table->string(column: 'home_steps_1_image');
            $table->string('home_steps_2_title');
            $table->string('home_steps_2_text');
            $table->string('home_steps_2_image');
            $table->string('home_steps_3_title');
            $table->string('home_steps_3_text');
            $table->string('home_steps_3_image');
            $table->string('home_steps_cta_label');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_steps');
    }
};
