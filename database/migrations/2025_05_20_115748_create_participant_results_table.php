<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TVET;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participant_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TVET::class)->nullable()->unique('t_v_e_t_s')->constrained();

            $table->string('vocational_skill');
            $table->decimal('average_score', 5, 2);
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_results');
    }
};
