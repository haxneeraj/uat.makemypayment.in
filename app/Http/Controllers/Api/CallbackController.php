<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\PayoutRefund;
use App\Jobs\WebhookRunner\SendPayoutWebhookJob;

class CallbackController extends Controller
{
    /**
     * @group Callbacks
     *
     * Partner 2FA Callback from SprintNXT.
     * Verifies the base64-encoded pipe-delimited payload against the request fields.
     */
    public function springNxt2FA(Request $request)
    {
        // return response()->json(['responsecode' => 200, 'message' => 'Success!', 'status' => true]);
        \Log::info('SprintNXT 2FA callback $_POST: ' . json_encode($request->all()));

        // Data comes as a POST field named 'data' containing a base64-encoded string
        // Format after decode: txnid|accno|ifsccode|amount[|checksum]
        $encodedData = $request->input('data');

        if (empty($encodedData)) {
            \Log::error('SprintNXT 2FA callback: missing data field');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $decodedData = base64_decode($encodedData, true);

        if ($decodedData === false) {
            \Log::error('SprintNXT 2FA callback: base64 decode failed');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        \Log::info('SprintNXT 2FA callback decoded: ' . $decodedData);

        // Parse pipe-delimited fields (may include a trailing checksum as 5th part)
        $parts = explode('|', $decodedData);
        if (count($parts) < 4) {
            \Log::error('SprintNXT 2FA callback: unexpected decoded format - ' . $decodedData);
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $txnid   = trim($parts[2]);

        // Look up by SprintNXT's transaction ID (stored in transaction_id)
        $payout = Payout::where('transaction_id', $txnid)->first();

        if (!$payout) {
            \Log::warning('SprintNXT 2FA callback: payout not found for transaction_id: ' . $txnid);
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        // 2FA callback = SprintNXT confirming transaction — mark as success
        $payout->update([
            'status'       => 'success',
            'processed_at' => now(),
        ]);

        \Log::info('SprintNXT 2FA callback: payout confirmed for transaction_id: ' . $txnid);

        return response()->json(['responsecode' => 200, 'message' => 'Success!', 'status' => true]);
    }

    /**
     * @group Callbacks
     *
     * Payout status callback from SprintNXT.
     *
     * Payload is a base64-encoded AES-256-CBC encrypted JSON string.
     * After decrypting, the payload shape is:
     * {
     *   "date": "...",
     *   "data": {
     *     "transferId": "...",
     *     "utr": "...",
     *     "remarks": "...",
     *     "bank_inquiry_remarks": "...",
     *     "status": 6,
     *     "nxtTxnId": "...",
     *     "narration": "..."
     *   }
     * }
     */
    public function sprintnxtCallback(Request $request)
    {
        \Log::info('SprintNXT payout callback raw POST: ' . json_encode($request->all()));

        // Callback token may arrive as form field data, JSON array index 0, or raw body.
        $encodedData = $request->input('data');

        if (empty($encodedData)) {
            $all = $request->all();
            $encodedData = $all[0] ?? null;
        }

        if (empty($encodedData)) {
            $rawBody = trim((string) $request->getContent());
            $encodedData = $rawBody !== '' ? trim($rawBody, "\" ") : null;
        }

        if (empty($encodedData)) {
            \Log::error('SprintNXT payout callback: missing data field');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $iv = env('SPRINTNXT_AES_ENCRYPTION_IV');
        $key = env('SPRINTNXT_AES_ENCRYPTION_KEY');

        if (empty($iv) || empty($key)) {
            \Log::error('SprintNXT payout callback: AES credentials are not configured');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $decodedCipher = base64_decode($encodedData, true);

        if ($decodedCipher === false) {
            \Log::error('SprintNXT payout callback: base64 decode failed');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $decrypted = openssl_decrypt(
            $decodedCipher,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted === false || $decrypted === '') {
            \Log::error('SprintNXT payout callback: decryption failed');
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        \Log::info('SprintNXT payout callback decrypted payload: ' . $decrypted);

        $payload = json_decode($decrypted, true);

        if (!is_array($payload)) {
            \Log::error('SprintNXT payout callback: invalid JSON payload', [
                'decrypted' => $decrypted,
            ]);
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $callbackData = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;

        $sprintnxtTxnId = trim((string) ($callbackData['nxtTxnId'] ?? ''));
        $transferId = trim((string) ($callbackData['transferId'] ?? ''));
        $txnStatus = (int) ($callbackData['status'] ?? -1);
        $utr = trim((string) ($callbackData['utr'] ?? ''));
        $remarks = trim((string) ($callbackData['remarks'] ?? ''));
        $bankInquiryRemarks = trim((string) ($callbackData['bank_inquiry_remarks'] ?? ''));
        $narration = trim((string) ($callbackData['narration'] ?? ''));

        if ($transferId === '' && $sprintnxtTxnId === '') {
            \Log::error('SprintNXT payout callback: missing transfer identifiers', [
                'payload' => $payload,
            ]);
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        // Look up by transferId (stored as transaction_id)
        $payout = Payout::where('transaction_id', $transferId)->first();

        // Fallback: look up by sprintnxt_txn_id
        if (!$payout && $sprintnxtTxnId) {
            $payout = Payout::where('sprintnxt_txn_id', $sprintnxtTxnId)->first();
        }

        if (!$payout) {
            \Log::warning('SprintNXT payout callback: payout not found', [
                'transferId'     => $transferId,
                'sprintnxtTxnId' => $sprintnxtTxnId,
            ]);
            return response()->json(['responsecode' => 400, 'message' => 'Failed!', 'status' => false]);
        }

        $dbStatus = match ($txnStatus) {
            0       => 'initiated',
            1       => ($utr ? 'success' : 'pending'),
            2       => 'pending',
            3       => 'send_to_bank',
            4       => 'failed',
            6       => 'processed',
            default => 'pending',
        };

        $updates = [
            'status'           => $dbStatus,
            'txn_status'       => $txnStatus,
            'sprintnxt_txn_id' => $sprintnxtTxnId ?: $payout->sprintnxt_txn_id,
        ];

        if ($utr) {
            $updates['utr'] = $utr;
        }

        $resolvedRemarks = $remarks ?: $bankInquiryRemarks ?: $narration;

        if ($resolvedRemarks && !$payout->remarks) {
            $updates['remarks'] = $resolvedRemarks;
        }

        if (in_array($dbStatus, ['success', 'failed', 'processed']) && !$payout->processed_at) {
            $updates['processed_at'] = now();
        }

        $payout->update($updates);

        // If payout failed, schedule a refund for next day processing
        if ($dbStatus === 'failed') {
            PayoutRefund::firstOrCreate(
                ['payout_id' => $payout->id],
                [
                    'user_id'      => $payout->user_id,
                    'amount'       => $payout->total_amount > 0 ? $payout->total_amount : $payout->amount,
                    'process_date' => now()->addDay()->toDateString(),
                    'status'       => 'pending',
                    'remarks'      => 'Payout failed via SprintNXT callback. Reason: ' . ($resolvedRemarks ?: 'N/A'),
                ]
            );

            \Log::info('Payout refund scheduled for next day.', [
                'payout_id'    => $payout->id,
                'amount'       => $payout->total_amount ?: $payout->amount,
                'process_date' => now()->addDay()->toDateString(),
            ]);
        }

        \Log::info('SprintNXT payout callback: payout updated', [
            'transaction_id' => $payout->transaction_id,
            'status'         => $dbStatus,
            'utr'            => $utr,
            'txn_status'     => $txnStatus,
        ]);

        // Fire outgoing webhook to the merchant if they have one configured
        // SendPayoutWebhookJob::dispatch($payout->user_id, [
        //     'event'          => 'payout.updated',
        //     'transaction_id' => $payout->transaction_id,
        //     'status'         => $dbStatus,
        //     'utr'            => $utr ?: null,
        //     'amount'         => $payout->amount,
        //     'sprintnxt_txn_id' => $sprintnxtTxnId ?: null,
        // ]);

        return response()->json(['responsecode' => 200, 'message' => 'Success!', 'status' => true]);
    }
}