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
        Schema::create('field_of_study_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $commonFields = [
            'Computer Science',
            'Information Technology',
            'Business Administration',
            'Accounting',
            'Engineering',
            'Marketing',
            'Human Resources',
            'Education',
            'Agriculture',
            'Health Sciences',
            'Law',
            'Social Work',
            'Economics',
            'Communications',
            'Graphic Design',
            'Entrepreneurship',
            'Project Management',
            'Digital Marketing',
            'Data Science',
            'Cybersecurity',
        ];

        foreach ($commonFields as $field) {
            DB::table('field_of_study_options')->insert([
                'name' => $field,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_of_studies');
    }
};
