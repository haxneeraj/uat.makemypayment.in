<?php

namespace App\Services\BankServices;

use Illuminate\Support\Facades\Http;

use App\Helpers\AESHelper;

class CastlerService extends AESHelper
{
    protected $baseurl;
    protected $apiKey;
    protected $apiSecret;
    protected $xApiKey;

    protected $token;

    public function __construct()
    {
        $this->baseurl = config('castler.BASEURL');

        $this->apiKey = config('castler.API_KEY');
        $this->apiSecret = config('castler.API_SECRET');
        $this->xApiKey = config('castler.X_API_KEY');
        
        parent::__construct();
    }

    protected function header($header = []):array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'x-api-key' => $this->xApiKey,
            'x-client-key' => $this->apiKey,
            ...$header
        ];
    }

    protected function isHtml($string) {
        return $string != strip_tags($string);
    }

    protected function responseResolver($response)
    {
        # Check for the success result and return the result
        if($response->successful() && isset($response->json()['success']) && $response->json()['success'] == true)
        {
            return $response->json()['result'];
        }

        # Return false
        return false;
    }


    /**
     * Create Account
     */
    public function createAccount(array $data)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.CREATE_ACCOUNT');

            # add company escrow account to the data
            $data = [
                'accountNumber' => config('castler.COMPANY_ESCROW_ACCOUNT'),
                ...$data
            ];

            # Encrypted Request Data
            $encrypted_data = $this->encrypt(json_encode($data));

            # Request
            $response = Http::withHeaders($this->header())
                ->withBody($encrypted_data, 'application/json')
                ->post($endpoint);

                \Log::info("Create Digital Escrow Account", [
                    'endpoint' => $endpoint,
                    'body_params' => $data,
                    'encrypted_body_params' => $encrypted_data,
                    'respons_status' => $response->status(),
                    'response_body' => $response->body(),
                    'decrypted_response_body' => $this->decrypt($response->body())
                ]);

            # If response success then decrypt and return
            if ($response->status() === 201) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Get Account lists
     */
    public function accountList()
    {
        # Endpoint
        $endpoint = $this->baseurl . config('castler.ENDPOINTS.ACCOUNT_LIST');

        # Response
        $response = HTTP::withHeaders($this->headerWithToken())
        ->get($endpoint);

        # Return Response
        return $this->responseResolver($response);
    }

    /**
     * Get Account By Account ID
     */
    public function accountById($id)
    {
        # Endpoint
        $endpoint = $this->baseurl . config('castler.ENDPOINTS.ACCOUNT_LIST');

        # Response
        $response = HTTP::withHeaders($this->headerWithToken())
        ->get($endpoint);

        # Return Response
        return $this->responseResolver($response);
    }

    /**
     * Get Account Balance
     */
    public function getAccountBalance(array $data) : int
    {
        try{
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.ACCOUNT_BALANCE');

            # Request
            $response = Http::withHeaders($this->header())
            ->get($endpoint, $data);

            # If response success then decrypt and return
            if ($response->status() === 200) {
                # check if response body is html then return 0
                if($this->isHtml($response->body())) {
                    return 0;
                }
                
                # decrypt response body
                $response = json_decode($this->decrypt($response->body()));

                # return balance in integer
                return (int) $response?->result?->balance;
            }

            return 0;
        }
        catch(\Exception $e)
        {
            return 0;
        }
    }

    /**
     * Add Source Account
     */
    public function addSourceAccount(array $data)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.ADD_SOURCE_ACCOUNT');

            # Encrypted Request Data
            $encrypted_data = $this->encrypt(json_encode($data));

            # Request
            $response = Http::withHeaders($this->header())
                ->withBody($encrypted_data, 'application/json')
                ->post($endpoint);

            \Log::info("Add Source Account", [
                'endpoint' => $endpoint,
                'body_params' => $data,
                'encrypted_body_params' => $encrypted_data,
                'respons_status' => $response->status(),
                'response_body' => $response->body(),
                'decrypted_response_body' => $this->decrypt($response->body())
            ]);

            # If response success then decrypt and return
            if ($response->status() === 201) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Toggle Source Account
     */
    public function toggleSourceAccount(array $data)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.TOGGLE_SOURCE_ACCOUNT');

            # Encrypted Request Data
            $encrypted_data = $this->encrypt(json_encode($data));

            # Request
            $response = Http::withHeaders($this->header())
                ->withBody($encrypted_data, 'application/json')
                ->patch($endpoint);

            # If response success then decrypt and return
            if ($response->status() === 200) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Create Payee
     */
    public function createPayee(array $data)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.CREATE_PAYEE');

            # Encrypted Request Data
            $encrypted_data = $this->encrypt(json_encode($data));

            # Request
            $response = Http::withHeaders($this->header())
                ->withBody($encrypted_data, 'application/json')
                ->post($endpoint);

            \Log::info("Create Payee", [
                'endpoint' => $endpoint,
                'body_params' => $data,
                'encrypted_body_params' => $encrypted_data,
                'respons_status' => $response->status(),
                'response_body' => $response->body(),
                'decrypted_response_body' => $this->decrypt($response->body())
            ]);

            # If Payee already added then get detail
            if($response->status() == 422) {
                $payee = $this->getPayeeListByAccountNumber($data['accountNumber']);

                # If payee already added then return
                if(!$payee) {
                    return false;
                }

                # Single Payee if result not blank
                $payee = json_decode($payee, true);
                if(!blank($payee['result']))
                {
                    $payeeId = $payee['result'][0]['payeeId'] ?? false;

                    return json_encode([
                        'result' => $payeeId,
                        'message' => null,
                        'errors' => [],
                        'success' => true,
                        'totalElements' => 0,
                    ]);
                }

                return false;                
            }

            

            # If response success then decrypt and return
            if ($response->status() == 201) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Get Payee List By Account Number
     */
    public function getPayeeListByAccountNumber($accountNumber)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.GET_PAYEE_LIST');

            # Request
            $response = Http::withHeaders($this->header())
            ->get($endpoint, ['accountNumber' => $accountNumber]);
            \Log::info("Get Payee List By Account Number", [
                'endpoint' => $endpoint,
                'body_params' => ['accountNumber' => $accountNumber],
                'respons_status' => $response->status(),
                'response_body' => $response->body(),
                'decrypted_response_body' => $this->decrypt($response->body())
            ]);

            # If response success then decrypt and return
            if ($response->status() === 200) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /*
     * Create Transfer Request
     */
    public function createTransferRequest(array $data)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.CREATE_TRANSFER_REQUEST');

            # Encrypted Request Data
            $encrypted_data = $this->encrypt(json_encode($data));

            # Request
            $response = Http::withHeaders($this->header())
                ->withBody($encrypted_data, 'application/json')
                ->post($endpoint);
            \Log::info("Create Transfer Request", [
                'endpoint' => $endpoint,
                'body_params' => $data,
                'encrypted_body_params' => $encrypted_data,
                'respons_status' => $response->status(),
                'response_body' => $response->body(),
                'decrypted_response_body' => $this->decrypt($response->body())
            ]);

            # If response success then decrypt and return
            if ($response->status() === 200) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    public function getTransferDetail($transferId)
    {
        try{            
            # Endpoint
            $endpoint = $this->baseurl . config('castler.ENDPOINTS.GET_TRANSFER_DETAIL'). '/' . $transferId;   

            # Request
            $response = Http::withHeaders($this->header())
            ->get($endpoint);
            \Log::info("Get Transfer Detail", [
                'endpoint' => $endpoint,
                'body_params' => ['transferId' => $transferId],
                'respons_status' => $response->status(),
                'response_body' => $response->body(),
                'decrypted_response_body' => $this->decrypt($response->body())
            ]);

            # If response success then decrypt and return
            if ($response->status() === 200) {
                return $this->decrypt($response->body());
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
}