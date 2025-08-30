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
        Schema::create('participant_message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained();
            $table->string('email');
            $table->enum('result', ['Awarded', 'Unsuccessful']);
            $table->text('status');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_message_logs');
    }
};
