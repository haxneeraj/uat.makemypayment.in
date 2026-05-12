<?php

namespace App\Services;
use App\Services\BankServices\CastlerService;

use App\Dto\SinglePayoutDTO;

use App\Models\Payee;
use App\Models\Payout;

use App\Models\User;
use App\Jobs\Payouts\UpdateTransactionStatusJob;

use Illuminate\Support\Facades\DB;

class PayoutService
{
    protected $castlerService;

    public function __construct(CastlerService $castlerService)
    {
        $this->castlerService = $castlerService;
    }

    public function payeeExists($accountNumber): bool
    {
        return Payee::where('account_number', $accountNumber)->exists();
    }

    public function getPayeeByAccountNumber($accountNumber): ?Payee
    {
        return Payee::where('account_number', $accountNumber)->first();
    }

    /**
     * Create Payee
     */
    public function createPayee($data)
    {
        $payee = Payee::where('account_number', $payout->accountNumber)->first();
        return $payee ? $payee->id : null;
    }

    public function createSinglePayout(SinglePayoutDTO $payout, User $user)
    {
        try
        {
            # Strat Transaction 
            DB::beginTransaction();

            # If account no exists then get payeeId else create a new payee
            if(!$this->payeeExists($payout->accountNumber))
            {
                $bankPayee = app(CastlerService::class)->createPayee([
                    'accountHolder' => $payout->accountHolder,
                    'accountNumber' => $payout->accountNumber,
                    'ifsc' => $payout->ifscCode,
                    'bankName' => $payout->bank,
                    'mobile' => $payout->mobile,
                    'email' => $payout->email,
                ]);

                if(!$bankPayee)
                {
                    return false;
                }

                $bankPayee = json_decode($bankPayee, true);

                if(empty($bankPayee['result']))
                {
                    return false;
                }

                $payeeId = $bankPayee['result'] ?? false;
                $payee = Payee::create([
                    'user_id' => $user->id,
                    'account_holder' => $payout->accountHolder,
                    'account_number' => $payout->accountNumber,
                    'ifsc_code' => $payout->ifscCode,
                    'bank_name' => $payout->bank,
                    'mobile' => $payout->mobile,
                    'email' => $payout->email,
                    'payee_id' => $payeeId,
                ]);
            }
            else{
                $payee = $this->getPayeeByAccountNumber($payout->accountNumber);
                $payeeId = $payee->payee_id;
            }

            # Get User VAN as Escrow ID
            $van = $user->merchantVirtualAccount;
            if(!$van)
            {
                return false;
            }

            $escrowId = $van->account_id;

            if(!$escrowId)
            {
                return false;
            }

            # Transfer the payment
            $response = app(CastlerService::class)->createTransferRequest([
                'escrowId' => $escrowId,
                'payeeId' => $payeeId,
                'mode' => 'imps',
                'amount' => $payout->amount,
                'purpose' => 'Testing',
            ]);

            if(!$response || empty(json_decode($response)->result))
            {
                return false;
            }

            $response = json_decode($response, true);

            # Create Payout Record
            Payout::create([
                'user_id' => $user->id,
                'payee_id' => $payee->id,
                'amount' => $payout->amount,
                'mode' => 'imps',
                'transaction_id' => $response['result']['transferId'],
                'remarks' => 'Initiated',
            ]);

            # Dispatch Job to Update Transaction Status
            dispatch(new UpdateTransactionStatusJob($response['result']['transferId']))->onQueue('payout');

            # Commit Transaction
            DB::commit();

            return $response['result'];
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            \Log::info('Payout Exception: ', ['message' => $e->getMessage()]);
            return false;
        }
        
    }


}