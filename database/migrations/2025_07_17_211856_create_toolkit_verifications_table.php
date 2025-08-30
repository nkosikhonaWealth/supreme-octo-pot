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
        Schema::create('toolkit_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('users')->onDelete('cascade'); 

            $table->boolean('toolkit_received')->default(false);
            $table->date('date_toolkit_received')->nullable();
            $table->date('date_of_visit')->nullable();
            $table->date('date_of_next_visit')->nullable();
            $table->integer('number_of_people_met')->nullable();

            $table->boolean('is_toolkit_used')->default(false);
            $table->text('is_toolkit_used_comment')->nullable();

            $table->boolean('condition_of_tools')->default(false);
            $table->text('condition_of_tools_comment')->nullable();

            $table->boolean('recipient_providing_services')->default(false);
            $table->text('recipient_providing_services_comment')->nullable();

            $table->boolean('visible_income_activity')->default(false);
            $table->text('visible_income_activity_comment')->nullable();

            $table->string('short_interview')->nullable();
            $table->text('short_interview_comment')->nullable();

            $table->enum('toolkit_usage_frequency', ['Daily', 'Weekly', 'Rarely', 'Not At All'])->nullable();

            $table->boolean('making_income')->default(false);
            $table->decimal('approximate_income_per_month', 10, 2)->nullable();

            $table->text('summary_of_activities')->nullable();
            $table->text('field_lessons')->nullable();

            $table->string('prepared_by')->nullable();
            $table->date('prepared_on')->nullable();
            $table->string('site_representative')->nullable();
            $table->date('site_signed_on')->nullable();            
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toolkit_verifications');
    }
};
