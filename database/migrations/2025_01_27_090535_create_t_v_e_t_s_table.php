<?php

use App\Models\Participant;
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
        Schema::create('t_v_e_t_s', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Participant::class);

            $table->string('vocational_skill');
            $table->string('current_activity');
            $table->string('duration');
            $table->string('toolkit_use');
            $table->string('recent_assistance');
            $table->json('certificate_upload');
            $table->json('finance_upload');
            $table->text('motivation');
            $table->string('account');
            $table->string('account_number');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_v_e_t_s');
    }
};
