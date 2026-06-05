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
        Schema::create('payout_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payout_id')->constrained('payouts')->onDelete('cascade');
            $table->foreignId('deposit_id')->constrained('deposits')->onDelete('cascade');
            $table->decimal('amount', 12, 2)->comment('Total amount to refund (payout amount + fee)');
            $table->date('process_date')->comment('Date on which this refund should be credited to wallet');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->text('remarks')->nullable()->comment('Reason for refund / failure notes');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['process_date', 'status']);
            $table->unique('payout_id'); // one refund per payout
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_refunds');
    }
};
