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
        Schema::create('event_attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Officer who submitted
            $table->string('stakeholder_level'); // International/National
            $table->string('region');
            $table->string('location');
            $table->string('event_type'); // Meeting, Workshop, etc
            $table->date('engagement_date');
            $table->date('report_date');
            $table->string('programme_area');
            $table->text('purpose');
            $table->longText('summary');
            $table->longText('key_themes')->nullable();
            $table->longText('key_stakeholders')->nullable();
            $table->longText('opportunities')->nullable();
            $table->json('action_items')->nullable(); // We'll store this as array [{item, responsible, timeline, status}]
            $table->longText('lessons')->nullable();
            $table->json('supporting_materials')->nullable(); // File uploads
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendance_reports');
    }
};
