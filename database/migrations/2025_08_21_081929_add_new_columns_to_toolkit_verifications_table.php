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
        Schema::table('toolkit_verifications', function (Blueprint $table) {
            // Business scalability and support tracking
            $table->boolean('received_other_support')->nullable()->after('site_signed_on'); // Replace 'existing_field' with actual last field
            $table->text('support_entity_details')->nullable()->comment('Details of entities providing support (YERF, CFI, ESNAU, etc.)');
            
            // Development group affiliations
            $table->boolean('affiliated_with_dev_groups')->nullable();
            $table->longText('dev_group_details')->nullable()->comment('Details about development groups/organizations - type, how they helped, etc.');
            
            // Future planning
            $table->longText('future_plans_12_months')->nullable()->comment('Plans and goals for next 12 months');
            
            // Metadata for tracking
            $table->timestamp('last_support_check_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toolkit_verifications', function (Blueprint $table) {
            //
        });
    }
};
