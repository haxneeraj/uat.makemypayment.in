<?php

namespace Database\Seeders;

use App\Models\Deposit;
use App\Models\MerchantVirtualAccount;
use App\Models\SourceAccount;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $virtualAccounts = MerchantVirtualAccount::query()
            ->where('status', 'active')
            ->get();

        if ($virtualAccounts->isEmpty()) {
            $this->command?->warn('No active merchant virtual accounts found. Please seed merchants first.');
            return;
        }

        $faker = fake();
        $targetCount = 120; // Keep above requested minimum (100)
        $startDate = Carbon::create(2026, 4, 1)->startOfDay();
        $endDate = now()->endOfDay();

        for ($i = 1; $i <= $targetCount; $i++) {
            /** @var MerchantVirtualAccount $virtualAccount */
            $virtualAccount = $virtualAccounts->random();

            $sourceAccount = SourceAccount::query()
                ->where('user_id', $virtualAccount->user_id)
                ->where('status', 'active')
                ->inRandomOrder()
                ->first();

            $transactionAt = Carbon::createFromTimestamp(
                random_int($startDate->timestamp, $endDate->timestamp)
            );

            $remitterAccount = $sourceAccount?->account_number ?? (string) random_int(1000000000, 9999999999);
            $remitterIfsc = $sourceAccount?->ifsc_code ?? $faker->randomElement([
                'HDFC0000123',
                'ICIC0000456',
                'UTIB0000789',
                'SBIN0000333',
                'KKBK0000555',
            ]);
            $remitterBank = $sourceAccount?->bank_name ?? $faker->randomElement([
                'HDFC Bank',
                'ICICI Bank',
                'Axis Bank',
                'State Bank of India',
                'Kotak Mahindra Bank',
            ]);
            $remitterName = $sourceAccount?->account_holder_name ?? $faker->name();
            $amount = round((float) random_int(5000, 500000), 2);
            $debitCredit = $faker->randomElement(['CR', 'DR']);
            $processingStatus = $faker->randomElement(['success', 'success', 'success', 'duplicate', 'technical_reject']);

            $alertSequenceNo = 'ALT' . $transactionAt->format('YmdHis') . strtoupper(Str::random(6)) . str_pad((string) $i, 4, '0', STR_PAD_LEFT);

            $alertPayload = [
                'Alert Sequence No' => $alertSequenceNo,
                'Remitter Name' => $remitterName,
                'Remitter Account' => $remitterAccount,
                'Remitter Bank' => $remitterBank,
                'User Reference Number' => 'REF' . strtoupper(Str::random(10)),
                'Virtual Account' => $virtualAccount->van,
                'Amount' => $amount,
                'Mnemonic Code' => $faker->randomElement(['NEFT', 'IMPS', 'RTGS']),
                'Transaction Date' => $transactionAt->toDateTimeString(),
                'Value Date' => $transactionAt->toDateString(),
                'IFSC Code' => $remitterIfsc,
                'Cheque No' => $faker->optional(0.15)->numerify('########'),
                'Transaction Description' => $faker->randomElement([
                    'Merchant inward transfer',
                    'Customer wallet load',
                    'Settlement credit',
                    'UPI to VA transfer',
                    'Fund add request',
                ]),
                'Account Number' => $virtualAccount->van,
                'Debit Credit' => $debitCredit,
            ];

            Deposit::create([
                'user_id'                 => $virtualAccount->user_id,
                'alert_sequence_no'       => $alertSequenceNo,
                'remitter_name'           => $remitterName,
                'remitter_account'        => $remitterAccount,
                'remitter_bank'           => $remitterBank,
                'user_reference_number'   => $alertPayload['User Reference Number'],
                'virtual_account'         => $virtualAccount->van,
                'amount'                  => $amount,
                'mnemonic_code'           => $alertPayload['Mnemonic Code'],
                'transaction_date'        => $transactionAt,
                'value_date'              => $transactionAt->toDateString(),
                'ifsc_code'               => $remitterIfsc,
                'cheque_no'               => $alertPayload['Cheque No'],
                'transaction_description' => $alertPayload['Transaction Description'],
                'account_number'          => $virtualAccount->van,
                'debit_credit'            => $debitCredit,
                'raw_payload'             => $alertPayload,
                'processing_status'       => $processingStatus,
                'created_at'              => $transactionAt,
                'updated_at'              => $transactionAt,
            ]);
        }

        $this->command?->info("InwardSeeder completed with {$targetCount} deposits.");
    }
}
