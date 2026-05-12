<?php

namespace App\Jobs\WebhookRunner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\APIActivationRequest;
use App\Models\WebhookLog;


use App\Helpers\API\SecurityHelper;

class SendPayoutWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5; // Max retries
    public $backoff = [60, 300, 900]; // Retry delays in seconds: 1 min, 5 min, 15 min
    public $timeout = 120; // HTTP timeout

    public $merchant_id;
    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($merchant_id, $payload)
    {
        $this->merchant_id = $merchant_id;
        $this->payload = $payload;

        // Specific queue
        $this->onQueue('webhooks-runner');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find the merchant by ID
        $merchant = APIActivationRequest::where('user_id', $this->merchant_id)->firstOrFail();

        // get merchant api_secret
        $api_secret = $merchant->user?->api_secret;
        // Check if api_secret is empty
        if (empty($api_secret)) {
            Log::error('Merchant API secret is empty', [
                'merchant_id' => $this->merchant_id,
            ]);
            $this->fail(new \Exception('Merchant API secret is empty'));
            return;
        }

        # Security Helper Class Instance        
        $securityHelper = new SecurityHelper($api_secret);
        

        // Create initial webhook log
        $log = WebhookLog::create([
            'user_id' => $this->merchant_id,
            'url' => $merchant->webhook_url,
            'payload' => json_encode($this->payload),
            'status' => 'pending',
        ]);

        # Encrypted payload
        $this->payload = $securityHelper->encrypt(json_encode($this->payload));

        try {

            $response = Http::timeout($this->timeout)
            ->withHeaders([
                'x-signature' => $merchant->webhook_secret,
                'Content-Type' => 'application/json',
            ])            
            ->withBody($this->payload)
            ->post($merchant->webhook_url);

            if ($response->status() === 200) {
                // Update log on success
                $log->update([
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'status' => 'success',
                ]);
                
                Log::info('Payout webhook sent successfully', [
                    'url' => $merchant->webhook_url,
                    'payload' => $this->payload,
                    'response' => $response->body(),
                ]);
            } else {
                // Update log on failure
                $log->update([
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'status' => 'failed',
                    'error_message' => 'Webhook failed with status ' . $response->status(),
                ]);

                Log::warning('Payout webhook failed', [
                    'url' => $merchant->webhook_url,
                    'payload' => json_encode($this->payload),
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                // Merchant must return HTTP 200 to acknowledge the webhook.
                // Any other response code is treated as failure and triggers a retry.
                $this->fail(new \Exception('Webhook request failed with status ' . $response->status()));
            }
        } catch (\Exception $e) {
            // Update log on exception
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error('Payout webhook exception', [
                'payload' => $this->payload,
                'message' => $e->getMessage(),
            ]);
            // Let Laravel handle retry
            throw $e;
        }
    }
}
