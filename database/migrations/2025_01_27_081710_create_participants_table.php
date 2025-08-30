<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);

            $table->string('gender');
            $table->date('d_o_b');
            $table->string('phone');
            $table->string('marital_status');
            $table->string('id_upload');
            $table->string('residential_address');
            $table->string('living_situation');
            $table->string('inkhundla');
            $table->string('pathway');
            $table->string('region');
            $table->string('family_situation');
            $table->string('family_role');
            $table->string('disability');
            $table->string('disability_name')->nullable();
            $table->string('beneficiaries');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
