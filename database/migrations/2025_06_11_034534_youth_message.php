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
        Schema::create('youth_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('inkhundla')->nullable();
            $table->string('vocational_skill')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Enables SoftDeletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('youth_messages');
    }
};
