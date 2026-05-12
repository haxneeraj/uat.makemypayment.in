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
        Schema::create('merchant_virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('van')->unique()->comment('Virtual Account Number');
            $table->string('account_holder')->comment('Account Holder e.g. Merchant Name');
            $table->string('ifsc')->nullable()->comment('IFSC Code of the Bank');
            $table->string('purpose')->comment('Purpose of the Virtual Account');
            $table->dateTimeTz('start_date', 3)->comment('Start Date of the Virtual Account in ISO 8601 format (UTC)');
            $table->integer('validity')->comment('Validity Period of the Virtual Account in Days');
            $table->decimal('balance', 10, 2)->default(0)->comment('Current Balance of the Virtual Account');
            $table->enum('status', ['active', 'inactive', 'suspended', 'frozen', 'closed'])->default('active')->comment('Status of the Virtual Account');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_virtual_accounts');
    }
};
