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
        Schema::create('home_headers', function (Blueprint $table) {
            $table->id();

            $table->string('home_header_title');
            $table->string('home_header_subtitle');
            $table->string('home_header_cta_label');
            $table->string('home_header_promo_text');
            $table->string('home_header_promo_image_1');
            $table->string('home_header_promo_image_2');
            $table->string('home_header_promo_image_3');
            $table->string('home_header_promo_image_4');
            $table->string('home_header_promo_cta');
            $table->string('home_header_image_1');
            $table->string('home_header_image_2');
            $table->string('home_header_image_3');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_headers');
    }
};
