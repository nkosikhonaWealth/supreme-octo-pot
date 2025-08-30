<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MindsetChange;
use App\Models\Entrepreneurship;
use App\Models\TVET;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MindsetChange::class)->nullable()->unique('mindset_changes');
            $table->foreignIdFor(Entrepreneurship::class)->nullable()->unique('entrepreneurships');
            $table->foreignIdFor(TVET::class)->nullable()->unique('t_v_e_t_s');

            $table->string('status');
            $table->string('recommendation')->nullable();
            $table->string('organisation')->nullable();
            $table->string('shortlist');
            $table->string('comment')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
