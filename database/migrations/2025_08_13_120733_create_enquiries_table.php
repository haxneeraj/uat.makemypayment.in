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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('message');
           $table->enum('type', [
                'support_request',
                'onboarding_request',
                'merchant_inquiry',
                'registration_request',
                'partnership_request',
                'feedback_suggestion',
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
