<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('youth_message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('youth_message_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('status');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('youth_message_logs');
    }
};
