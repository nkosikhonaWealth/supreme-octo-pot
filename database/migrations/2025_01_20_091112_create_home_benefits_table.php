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
        Schema::create('home_benefits', function (Blueprint $table) {
            $table->id();

            $table->string('home_benefits_title');
            $table->string('home_benefits_subtitle');
            $table->string('home_benefits_intro');
            $table->string('home_benefits_list');
            $table->string('home_benefits_outro');
            $table->string('home_benefits_cta_label');
            $table->string('home_benefits_cta_image');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_benefits');
    }
};
