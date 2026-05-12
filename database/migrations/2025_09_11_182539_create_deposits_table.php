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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('alert_sequence_no', 50)->unique();
            $table->string('remitter_name', 100)->nullable();
            $table->string('remitter_account', 35)->nullable();
            $table->string('remitter_bank', 100)->nullable();
            $table->string('user_reference_number', 50)->nullable();
            $table->string('virtual_account', 35)->nullable();
            $table->decimal('amount', 13, 2);
            $table->string('mnemonic_code', 20)->nullable();
            $table->dateTime('transaction_date');
            $table->date('value_date');
            $table->string('ifsc_code', 20)->nullable();
            $table->string('cheque_no', 50)->nullable();
            $table->string('transaction_description', 120)->nullable();
            $table->string('account_number', 35);
            $table->string('debit_credit', 10);
            $table->json('raw_payload')->nullable();
            $table->enum('processing_status', ['success', 'duplicate', 'technical_reject'])->default('success');
            $table->timestamps();

            $table->index(['account_number', 'debit_credit']);
            $table->index(['transaction_date']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
