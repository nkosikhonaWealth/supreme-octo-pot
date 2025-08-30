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
        Schema::create('c_y_c_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')->constrained()->onDelete('cascade');

            $table->text('sdg_response')->nullable();
            $table->text('challenge_response')->nullable(); 
            $table->text('representation_experience')->nullable(); 
            $table->text('representation_details')->nullable(); 
            $table->text('leadership_experience')->nullable(); 
            $table->text('motivation')->nullable();

            $table->json('cv_upload')->nullable();
            $table->json('supporting_documents')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_y_c_applications');
    }
};
