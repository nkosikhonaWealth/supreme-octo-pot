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
        Schema::create('entrepreneurships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Participant::class);

            $table->string('education');
            $table->string('business_stage');
            $table->string('business_details');
            $table->string('business_offering');
            $table->string('business_revenue');
            $table->string('business_costs');
            $table->string('business_assistance');
            $table->string('assistance_cost');
            $table->string('assistance_beneficiaries');
            $table->string('business_upload');
            $table->string('finance_upload');
            $table->string('account');
            $table->string('account_number');
            $table->text('motivation');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrepreneurships');
    }
};
