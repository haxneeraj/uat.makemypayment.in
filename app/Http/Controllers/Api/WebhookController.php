<?php

namespace App\Http\Controllers\Api;

use App\Events\InwardPaymentReceived;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MerchantVirtualAccount;
use App\Models\SourceAccount;
use App\Models\Deposit;
use App\Models\WebhookLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class WebhookController extends Controller
{
    /**
     * @group Webhooks
     *
     * Receives inbound HDFC GenericCorporateAlertRequest payload.
     * Validates remitter against merchant's registered source accounts,
     * then records the deposit and credits the merchant's wallet.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Webhook received.', ['payload' => $payload]);

        // ── 1. Parse alert ────────────────────────────────────────────
        $alerts = $payload['GenericCorporateAlertRequest'] ?? null;
        $alert  = is_array($alerts) ? ($alerts[0] ?? null) : null;

        $alertSequenceNo = $this->field($alert, ['Alert Sequence No', 'AlertSequenceNo']);
        $vanNumber       = $this->field($alert, ['Virtual Account']);
        $remitterAccount = $this->field($alert, ['Remitter Account']);
        $remitterIfsc    = $this->field($alert, ['IFSC Code', 'Remitter IFSC']);
        $debitCredit     = $this->field($alert, ['Debit Credit']);
        $amount          = $this->field($alert, ['Amount']);
        $transactionDate = $this->field($alert, ['Transaction Date']);
        $valueDate       = $this->field($alert, ['Value Date']);
        $accountNumber   = $this->field($alert, ['Account number', 'Account Number']);

        $seqNo = (string) ($alertSequenceNo ?? '');

        // ── 2. Mandatory field check ──────────────────────────────────
        if (!$alert || !$alertSequenceNo || !$vanNumber || !$remitterAccount || !$remitterIfsc || !$amount || !$debitCredit || !$transactionDate || !$valueDate) {
            Log::warning('Webhook rejected: missing mandatory fields.', compact('alertSequenceNo', 'vanNumber'));
            return response()->json($this->response('1', 'Technical Reject', $seqNo), 200);
        }

        // ── 3. Resolve merchant via VAN ───────────────────────────────
        $virtualAccount = MerchantVirtualAccount::where('van', $vanNumber)->first();

        if (!$virtualAccount) {
            Log::warning('Webhook rejected: VAN not found.', ['van' => $vanNumber]);
            return response()->json($this->response('1', 'Technical Reject', $seqNo), 200);
        }

        $merchant = $virtualAccount->user;

        // ── 4. Duplicate check ────────────────────────────────────────
        if (Deposit::where('alert_sequence_no', $alertSequenceNo)->exists()) {
            Log::info('Webhook duplicate.', ['alert_sequence_no' => $alertSequenceNo]);
            $this->log($request, $payload, $this->response('0', 'Duplicate', $seqNo), 'success', $merchant->id, 200, null);
            return response()->json($this->response('0', 'Duplicate', $seqNo), 200);
        }

        // ── 5. Validate remitter against merchant's source accounts ───
        $sourceAccountExists = SourceAccount::where('user_id', $merchant->id)
            ->where('account_number', $remitterAccount)
            ->where('status', 'active')
            ->exists();

        if (!$sourceAccountExists) {
            Log::warning('Webhook rejected: remitter not in source accounts.', [
                'user_id'         => $merchant->id,
                'remitter_account' => $remitterAccount,
                'remitter_ifsc'   => $remitterIfsc,
            ]);
            $this->log($request, $payload, $this->response('1', 'Technical Reject', $seqNo), 'failed', $merchant->id, 200, 'Remitter not in registered source accounts');
            return response()->json($this->response('1', 'Technical Reject', $seqNo), 200);
        }

        // ── 6. Record deposit + credit wallet ─────────────────────────
        try {
            $deposit = null;

            DB::transaction(function () use (
                $merchant, $virtualAccount, $alert, $alertSequenceNo,
                $remitterAccount, $remitterIfsc, $vanNumber,
                $amount, $debitCredit, $accountNumber,
                $transactionDate, $valueDate, &$deposit
            ) {
                $deposit = Deposit::create([
                    'user_id'                => $merchant->id,
                    'alert_sequence_no'      => (string) $alertSequenceNo,
                    'remitter_name'          => $this->field($alert, ['Remitter Name']),
                    'remitter_account'       => $remitterAccount,
                    'remitter_bank'          => $this->field($alert, ['Remitter Bank']),
                    'user_reference_number'  => $this->field($alert, ['User Reference Number']),
                    'virtual_account'        => $vanNumber,
                    'amount'                 => $amount,
                    'mnemonic_code'          => $this->field($alert, ['Mnemonic Code']),
                    'transaction_date'       => $transactionDate,
                    'value_date'             => $valueDate,
                    'ifsc_code'              => $remitterIfsc,
                    'cheque_no'              => $this->field($alert, ['Cheque No']),
                    'transaction_description'=> $this->field($alert, ['Transaction Description']),
                    'account_number'         => $accountNumber,
                    'debit_credit'           => $debitCredit,
                    'raw_payload'            => $alert,
                    'processing_status'      => 'success',
                ]);

                // Credit merchant wallet (VAN balance)
                $virtualAccount->increment('balance', (float) $amount);
            });

            if ($deposit) {
                event(new InwardPaymentReceived($deposit->fresh(['user'])));
            }

            $response = $this->response('0', 'Success', $seqNo);
            $this->log($request, $payload, $response, 'success', $merchant->id, 200, null);
            return response()->json($response, 200);

        } catch (Throwable $e) {
            Log::error('Webhook processing failed.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $response = $this->response('1', 'Technical Reject', $seqNo);
            $this->log($request, $payload, $response, 'failed', $merchant->id, 500, $e->getMessage());
            return response()->json($response, 200);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────

    private function field(?array $payload, array $keys): ?string
    {
        if (!$payload) return null;

        foreach ($keys as $key) {
            if (isset($payload[$key]) && $payload[$key] !== '') {
                return (string) $payload[$key];
            }
        }

        return null;
    }

    private function response(string $errorCode, string $errorMessage, string $domainReferenceNo): array
    {
        return [
            'GenericCorporateAlertResponse' => [
                'errorCode'        => $errorCode,
                'errorMessage'     => $errorMessage,
                'domainReferenceNo'=> $domainReferenceNo,
            ],
        ];
    }

    private function log(Request $request, array $payload, array $response, string $status, ?int $userId, int $statusCode, ?string $errorMessage): void
    {
        try {
            if (!$userId) {
                Log::warning('Webhook log skipped: no user_id.', ['url' => $request->fullUrl(), 'status' => $status]);
                return;
            }

            WebhookLog::create([
                'user_id'       => $userId,
                'url'           => $request->fullUrl(),
                'payload'       => $payload,
                'status_code'   => $statusCode,
                'response_body' => json_encode($response),
                'status'        => $status,
                'error_message' => $errorMessage,
            ]);
        } catch (Throwable $e) {
            Log::error('Webhook log write failed.', ['error' => $e->getMessage()]);
        }
    }
}