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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('merchant_id')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');


            $table->rememberToken();
            $table->string('profile_photo', 2048)->nullable();

            $table->string('role');
            $table->bigInteger('daily_transfer_limit')->default(5000000);
            $table->integer('min_transfer_limit')->default(100);
            $table->integer('max_transfer_limit')->default(200000);
            $table->decimal('below_thousand_charge', 10, 2)->default(10.03)->comment('fixed');
            $table->decimal('above_thousand_charge', 20, 2)->default(1.003)->comment('percentage %');

            $table->integer('max_source_accounts')->default(3);

            $table->string('api_key', 256)->nullable();
            $table->string('api_secret', 256)->nullable();

            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('kyc_status', ['pending', 'verified', 'rejected', 'submitted'])->default('pending')->comment('KYC Status');
            $table->enum('van_status', ['pending', 'verified', 'rejected'])->default('pending')->comment('Virtual Account Status');

            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
