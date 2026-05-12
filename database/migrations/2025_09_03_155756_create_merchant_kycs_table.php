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
        Schema::create('merchant_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Common fields
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->enum('business_type', ['proprietor', 'private_limited', 'llp', 'partnership', 'ngo/trust', 'other'])->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('country')->nullable();

            $table->string('website_url')->nullable();
            $table->string('apk_link')->nullable();

            // Individual fields
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();

            # Pan
            $table->string('pan', 10)->nullable(); // Either pan or tan of the 'proprietor/director
            $table->string('pan_front')->nullable();

            # Company Pan
            $table->string('company_pan', 10)->nullable(); // Either pan or tan of the 'company'
            $table->string('company_pan_front')->nullable();

            # CIN
            $table->string('cin_number')->nullable();
            $table->string('cin_front')->nullable();

            #Aadhar
            $table->string('aadhaar', 12)->nullable();
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();
            # Cancelled Cheque and Bank Statement
            $table->string('cancelled_cheque')->nullable();
            $table->string('bank_statement')->nullable();

            # Bank Details
            $table->string('bank_name', 500)->nullable();
            $table->string('branch')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->enum('account_type', ['SAVING', 'CURRENT'])->nullable();

            # Proprietor and incorporation documents
            $table->string('proprietor_photo')->nullable()->comment('with stamp');
            $table->string('registration_certificate')->nullable()->comment('incorporation/udyam/msme');
            # Address Proof -- Rental Agreement or Electricity/Utility Bill
            $table->string('address_proof')->nullable()->comment('Rental Agreement or Electricity/Utility Bill');
            # GST fields
            $table->string('gstin', 15)->nullable();
            $table->string('gst_certificate')->nullable();

            // Business fields
            $table->string('document_aoa')->nullable();
            $table->string('document_moi')->nullable();
            $table->string('document_coi')->nullable();

            // Step
            $table->enum('step', ['1', '2', '3', '4', '5'])->default('1');

            // KYC Remark
            $table->text('kyc_remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_kycs');
    }
};
