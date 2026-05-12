<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\PayoutService;
use App\Dto\SinglePayoutDTO;

use App\Helpers\API\SecurityHelper;

class PayoutController extends Controller
{

    /**
     * Initiate a payout.
     * 
     * Start a new payout transaction for the authenticated merchant.
     *
     * **Note:** The request body must be:
     *  - AES encrypted (AES/CBC/PKCS5Padding)
     *  - Base64 encoded after encryption
     *  - Raw JSON payload before encryption should include: account_holder, account_number, bank_name, ifsc_code, amount, email, mobile
    *  - Merchant pre-validation is enforced before request processing:
     *    API key/secret, merchant status=active, kyc_status=verified,
     *    van_status=verified, verified IP/webhook configuration.
     *
     * **Outbound Webhook (Status Notification):**
     *
     * When the payout status changes (e.g., `processing`, `success`, `failed`), the system will
     * automatically POST an **AES-256-CBC encrypted, Base64-encoded** payload to your registered webhook URL.
     *
     * The raw JSON payload before encryption contains:
     * ```json
     * {
     *   "transaction_id": "TXN987654321",
     *   "beneficiary_account_holder": "John Doe",
     *   "beneficiary_account_number": "1234567890",
     *   "beneficiary_bank_name": "HDFC Bank",
     *   "beneficiary_ifsc_code": "HDFC0001234",
     *   "amount": 500,
     *   "status": "success",
     *   "utr": "UTR1234567890",
     *   "remarks": "Payment for services",
     *   "narration": "Salary"
     * }
     * ```
     *
     * The encrypted string is sent as the raw POST body with headers:
     * - `Content-Type: application/json`
     * - `x-signature: <your_webhook_secret>`
     *
     * **Your webhook endpoint MUST respond with HTTP 200** to acknowledge receipt.
     * Any other response code is treated as failure and the system will retry up to 5 times
     * (at 1 min, 5 min, and 15 min intervals).
     *
     * To decrypt the webhook payload, use AES-256-CBC with your `api_secret` as the key
     * and the fixed IV `0g7H#8X2mTqjvLwR`, then Base64-decode the body first.
     *
     * @group Payouts
     * 
     * @header X-API-KEY string required Your API key for authentication.
     * @header X-API-SECRET string required Your API secret for decryption and authentication.
     *
     * @bodyParam account_holder string required The full name of the bank account holder. Example: John Doe
     * @bodyParam account_number string required The bank account number. Example: 1234567890
     * @bodyParam bank_name string required The name of the bank. Example: HDFC Bank
     * @bodyParam ifsc_code string required The IFSC code of the bank branch. Example: HDFC0001234
     * @bodyParam amount numeric required The payout amount (must be at least 100). Example: 500
     * @bodyParam email string required The email address of the beneficiary. Example: user@example.com
     * @bodyParam mobile numeric required The mobile number of the beneficiary (10 digits). Example: 9876543210
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Payout initiated successfully",
     *   "data": "TXN987654321",
     *   "errors": null
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "API key and secret are required",
     *   "data": null,
     *   "errors": []
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "Request body is not valid base64 encoded",
     *   "data": null,
     *   "errors": []
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "Failed to decrypt data",
     *   "data": null,
     *   "errors": []
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "Invalid JSON format after decryption",
     *   "data": null,
     *   "errors": []
     * }
     * @response 401 {
     *   "status": false,
     *   "message": "Invalid API key or secret",
     *   "data": null,
     *   "errors": []
     * }
    * @response 403 {
    *   "status": false,
    *   "message": "Merchant KYC is not verified.",
    *   "data": null,
    *   "errors": []
    * }
     * @response 422 {
     *   "status": false,
     *   "message": "Validation failed",
     *   "data": null,
     *   "errors": {
    *     "account_number": ["The account number field is required."],
    *     "amount": ["The amount must be at least merchant min transfer limit."]
     *   }
     * }
     * @response 500 {
     *   "status": false,
     *   "message": "Failed to initiate payout. Please try again.",
     *   "data": null,
     *   "errors": []
     * }
     */
    public function initiate(Request $request)
    {
        $securityHelper = $this->securityHelper($request);
        $user = $this->merchant($request);

        $body_data = $this->decryptRequestBody($request, $securityHelper);
        if (!is_array($body_data)) {
            return $body_data;
        }

        $minAmount = (float) $user->min_transfer_limit;
        $maxAmount = (float) $user->max_transfer_limit;

        # Validation
        $validator = Validator::make($body_data, [
            'account_holder'      => 'required|string|max:255',
            'account_number'      => 'required|string',
            'ifsc_code'           => 'required|string',
            'bank_name'           => 'required|string',
            'branch_name'         => 'required|string',
            'branch_code'         => 'required|string',
            'mobile'              => 'required|digits:10',
            'city'                => 'required|string',
            'beneficiary_address' => 'required|string',
            'amount'              => "required|numeric|min:{$minAmount}|max:{$maxAmount}",
            'mode'                => 'required|string|in:imps,neft,rtgs,a2a',
            'purpose'             => 'required|string',
            'email'               => 'nullable|email',
            'state'               => 'nullable|alpha',
            'pincode'             => 'nullable|digits:6',
            'remarks'             => 'nullable|string',
            'narration'           => 'nullable|string',
        ], [
            'amount.min' => "The amount must be at least {$user->min_transfer_limit}.",
            'amount.max' => "The amount may not be greater than {$user->max_transfer_limit}.",
        ]);

        if ($validator->fails()) {
            return $this->encryptedError($securityHelper, 'Validation failed', $validator->errors()->toArray(), 422);
        }

        $payout_dto = new SinglePayoutDTO(
            accountHolder:      $body_data['account_holder'],
            accountNumber:      $body_data['account_number'],
            ifscCode:           $body_data['ifsc_code'],
            bankName:           $body_data['bank_name'],
            branchName:         $body_data['branch_name'],
            branchCode:         $body_data['branch_code'],
            mobile:             $body_data['mobile'],
            city:               $body_data['city'],
            beneficiaryAddress: $body_data['beneficiary_address'],
            amount:             (float) $body_data['amount'],
            mode:               $body_data['mode'],
            purpose:            $body_data['purpose'],
            email:              $body_data['email'] ?? null,
            state:              $body_data['state'] ?? null,
            pincode:            $body_data['pincode'] ?? null,
            remarks:            $body_data['remarks'] ?? null,
            narration:          $body_data['narration'] ?? null,
            type:               '1', // default to 1 for single payout
        );

        $response = app(PayoutService::class)->createSinglePayout($payout_dto, $user);
        if(!$response)
        {
            return $this->encryptedError($securityHelper, 'Failed to initiate payout. Please try again.', [], 500);
        }

        $successResponse = $this->successResponse($response, 'Payout initiated successfully');
        $successJsonString = $successResponse->getContent();
        return $securityHelper->encrypt($successJsonString);
    }

    /**
     * Check payout status.
     * 
     * Get the status of a specific payout transaction for the authenticated merchant.
     *
     * **Note:** 
     * - Response will be AES encrypted using the merchant's API secret.
     * - API key and secret must be sent in request headers for authentication.
    * - Merchant pre-validation is enforced before request processing.
     *
     * @group Payouts
     *
     * @header X-API-KEY string required Your API key for authentication.
     * @header X-API-SECRET string required Your API secret for encryption and authentication.
     *
     * @urlParam transaction_id string required The transaction ID of the payout. Example: TXN123456
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Payout status retrieved successfully",
     *   "data": {
     *     "transaction_id": "TXN123456",
     *     "beneficiary": {
     *       "account_holder": "John Doe",
     *       "account_number": "1234567890",
     *       "bank_name": "HDFC Bank",
     *       "ifsc_code": "HDFC0001234"
     *     },
     *     "amount": "1000.00",
     *     "status": "success",
     *     "utr": "UTR123456789",
     *     "remarks": "Payment processed successfully"
     *   },
     *   "errors": null
     * }
     * @response 404 {
     *   "status": false,
     *   "message": "Payout not found",
     *   "data": null,
     *   "errors": []
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "API key and secret are required",
     *   "data": null,
     *   "errors": []
     * }
     * @response 401 {
     *   "status": false,
     *   "message": "Invalid API key or secret",
     *   "data": null,
     *   "errors": []
     * }
    * @response 403 {
    *   "status": false,
    *   "message": "Request IP is not whitelisted for this merchant.",
    *   "data": null,
    *   "errors": []
    * }
     */
    public function checkStatus(Request $request)
    {
        $securityHelper = $this->securityHelper($request);
        $user = $this->merchant($request);

        $body_data = $this->decryptRequestBody($request, $securityHelper);
        if (!is_array($body_data)) {
            return $body_data;
        }

        # Validation
        $validator = Validator::make($body_data, [
            'transaction_id'      => 'required|string|max:255',
        ]);

        if($validator->fails())
        {
            return $this->encryptedError($securityHelper, 'Validation failed', $validator->errors()->toArray(), 422);
        }

        $payout = $user->payouts()->where('transaction_id', $body_data['transaction_id'])->first();

        if(!$payout)
        {
            return $this->encryptedError($securityHelper, 'Payout not found', [], 404);
        }
        

        # Response
        $response = $this->successResponse([
            'transaction_id' => $payout->transaction_id,
            'beneficiary' => [
                'account_holder' => $payout->payee?->account_holder,
                'account_number' => $payout->payee?->account_number,
                'bank_name' => $payout->payee?->bank_name,
                'ifsc_code' => $payout->payee?->ifsc_code,
            ],
            'amount' => $payout->amount,
            'status' => $payout->status,
            'utr' => $payout->utr,
            'remarks' => $payout->remarks,
            'narration' => $payout->narration,
        ], 'Payout status retrieved successfully');

        
        $jsonString = $response->getContent();

        # Encrypt
        return $securityHelper->encrypt($jsonString);
    }

    /**
     * Initiate bulk payouts.
     *
     * Accepts an array of payout objects, persists each as 'initiated',
     * and dispatches async jobs to process them via SprintNXT.
     *
     * **Note:** AES encrypted request / response same as initiate payout.
    * Merchant pre-validation is enforced before request processing.
     *
     * @group Payouts
     *
     * @header X-API-KEY string required
     * @header X-API-SECRET string required
     *
     * @bodyParam payouts array required Array of payout objects. Each item has the same fields as initiate payout.
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Bulk payout initiated",
     *   "data": {"transaction_ids": ["ABCDEF1234567890"]},
     *   "errors": null
     * }
    * @response 403 {
    *   "status": false,
    *   "message": "Merchant API activation is not verified.",
    *   "data": null,
    *   "errors": []
    * }
     */
    public function bulk(Request $request)
    {
        $securityHelper = $this->securityHelper($request);
        $user = $this->merchant($request);

        $body_data = $this->decryptRequestBody($request, $securityHelper);
        if (!is_array($body_data)) {
            return $body_data;
        }

        $minAmount = (float) $user->min_transfer_limit;
        $maxAmount = (float) $user->max_transfer_limit;

        $validator = Validator::make($body_data, [
            'payouts'                        => 'required|array|min:1|max:100',
            'payouts.*.account_holder'       => 'required|string|max:255',
            'payouts.*.account_number'       => 'required|string',
            'payouts.*.ifsc_code'            => 'required|string',
            'payouts.*.bank_name'            => 'required|string',
            'payouts.*.branch_name'          => 'required|string',
            'payouts.*.branch_code'          => 'required|string',
            'payouts.*.mobile'               => 'required|digits:10',
            'payouts.*.city'                 => 'required|string',
            'payouts.*.beneficiary_address'  => 'required|string',
            'payouts.*.amount'               => "required|numeric|min:{$minAmount}|max:{$maxAmount}",
            'payouts.*.mode'                 => 'required|string|in:imps,neft,rtgs,a2a',
            'payouts.*.purpose'              => 'required|string',
            'payouts.*.email'                => 'nullable|email',
            'payouts.*.state'                => 'nullable|alpha',
            'payouts.*.pincode'              => 'nullable|digits:6',
            'payouts.*.remarks'              => 'nullable|string',
            'payouts.*.narration'            => 'nullable|string',
        ], [
            'payouts.*.amount.min' => "Each payout amount must be at least {$user->min_transfer_limit}.",
            'payouts.*.amount.max' => "Each payout amount may not be greater than {$user->max_transfer_limit}.",
        ]);

        if ($validator->fails()) {
            return $this->encryptedError($securityHelper, 'Validation failed', $validator->errors()->toArray(), 422);
        }

        $dtos = array_map(fn($p) => new SinglePayoutDTO(
            accountHolder:      $p['account_holder'],
            accountNumber:      $p['account_number'],
            ifscCode:           $p['ifsc_code'],
            bankName:           $p['bank_name'],
            branchName:         $p['branch_name'],
            branchCode:         $p['branch_code'],
            mobile:             $p['mobile'],
            city:               $p['city'],
            beneficiaryAddress: $p['beneficiary_address'],
            amount:             (float) $p['amount'],
            mode:               $p['mode'],
            purpose:            $p['purpose'],
            email:              $p['email'] ?? null,
            state:              $p['state'] ?? null,
            pincode:            $p['pincode'] ?? null,
            remarks:            $p['remarks'] ?? null,
            narration:          $p['narration'] ?? null,
            type:               '1', // default to 1 for single payout
        ), $body_data['payouts']);

        try {
            $transactionIds = app(PayoutService::class)->createBulkPayout($dtos, $user);
            $success = $this->successResponse(['transaction_ids' => $transactionIds], 'Bulk payout initiated');
            return $securityHelper->encrypt($success->getContent());
        } catch (\Exception $e) {
            \Log::error('Bulk payout error: ' . $e->getMessage());
            return $this->encryptedError($securityHelper, 'Failed to initiate bulk payout. Please try again.', [], 500);
        }
    }

    /**
     * Get account balance.
     * 
     * Retrieve the current balance of the authenticated merchant's virtual account.
     *
     * **Note:** 
     * - Response will be AES encrypted using the merchant's API secret.
     * - API key and secret must be sent in request headers for authentication.
    * - Merchant pre-validation is enforced before request processing.
     *
     * @group Account
     *
     * @header X-API-KEY string required Your API key for authentication.
     * @header X-API-SECRET string required Your API secret for encryption and authentication.
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Account balance retrieved successfully",
     *   "data": {
     *     "balance": 15000.50
     *   },
     *   "errors": null
     * }
     * @response 400 {
     *   "status": false,
     *   "message": "API key and secret are required",
     *   "data": null,
     *   "errors": []
     * }
     * @response 401 {
     *   "status": false,
     *   "message": "Invalid API key or secret",
     *   "data": null,
     *   "errors": []
     * }
    * @response 403 {
    *   "status": false,
    *   "message": "Merchant virtual account is not verified.",
    *   "data": null,
    *   "errors": []
    * }
     */
    public function getBalance(Request $request)
    {
        $securityHelper = $this->securityHelper($request);
        $user = $this->merchant($request);
        $virtualAccount = $user->merchantVirtualAccount;

        if (!$virtualAccount || blank($virtualAccount->van)) {
            return $this->encryptedError($securityHelper, 'Merchant virtual account is not configured.', [], 422);
        }

        $balance = (float) $virtualAccount->balance;

        # Response 
        $response = $this->successResponse(['balance' => $balance], 'Account balance retrieved successfully');
        $jsonString = $response->getContent();

        # Encrypt
        return $securityHelper->encrypt($jsonString);
    }

    private function merchant(Request $request)
    {
        return $request->attributes->get('merchant_user');
    }

    private function securityHelper(Request $request): SecurityHelper
    {
        return $request->attributes->get('api_security_helper');
    }

    private function decryptRequestBody(Request $request, SecurityHelper $securityHelper)
    {
        $rawBody = trim($request->getContent());
        if (empty($rawBody)) {
            return $this->encryptedError($securityHelper, 'Request body is empty', [], 400);
        }

        if (base64_encode(base64_decode($rawBody, true)) !== $rawBody) {
            return $this->encryptedError($securityHelper, 'Request body is not valid base64 encoded', [], 400);
        }

        $decryptedJson = $securityHelper->decrypt($rawBody);
        if (!$decryptedJson) {
            return $this->encryptedError($securityHelper, 'Failed to decrypt data', [], 400);
        }

        $bodyData = json_decode($decryptedJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->encryptedError($securityHelper, 'Invalid JSON format after decryption', [], 400);
        }

        return $bodyData;
    }

    private function encryptedError(SecurityHelper $securityHelper, string $message, array $errors = [], int $code = 400)
    {
        $response = $this->errorResponse($message, $errors, $code);
        return $securityHelper->encrypt($response->getContent());
    }
}