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
        Schema::create('youth_attendance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_attendance_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('surname');
            $table->integer('age');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('youth_region');
            $table->string('education_level');
            $table->string('institution');
            $table->boolean('is_employed')->default(false);
            $table->enum('employment_type', ['Formal', 'Informal', 'Piece Work'])->nullable();
            $table->string('contact');
            $table->string('email')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youth_attendance_entries');
    }
};
