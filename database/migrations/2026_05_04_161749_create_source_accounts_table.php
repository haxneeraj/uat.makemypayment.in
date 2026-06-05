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
        Schema::create('source_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('document_type');
            $table->boolean('is_primary')->default(false);
            $table->string('remarks')->nullable();
            $table->string('document')->nullable(); // to store document path if needed
            $table->enum('status', ['active', 'inactive'])->default('active'); // active mean approved by admin, inactive means pending or rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_accounts');
    }
};
