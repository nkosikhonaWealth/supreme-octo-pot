<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Participant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_o_t_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Participant::class);

            $table->json('certificate_upload');
            $table->json('cv_upload');
            $table->string('current_activity')->nullable();
            $table->text('motivation');
            $table->string('youth_organization_response')->nullable();
            $table->string('youth_organization_name')->nullable();
            $table->text('youth_organization_duties')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_o_t_applications');
    }
};
