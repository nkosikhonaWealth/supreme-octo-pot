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
        Schema::create('internal_attendance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_attendance_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('institution');
            $table->string('designation');
            $table->string('contact');
            $table->string('email');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_attendance_entries');
    }
};
