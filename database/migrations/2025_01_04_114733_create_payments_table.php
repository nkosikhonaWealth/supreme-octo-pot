<?php

use App\Models\Booking;
use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Booking::class);
            $table->foreignIdFor(User::class);

            $table->date('payment_date');
            $table->string('payment_name')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('payment_amount');
            $table->string('payment_upload')->nullable();
            $table->string('payment_verification');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
