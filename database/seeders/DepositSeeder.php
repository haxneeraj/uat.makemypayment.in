<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DepositSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');

        $merchants = User::where('role', 'merchant')->get();

        if ($merchants->isEmpty()) {
            $this->command->warn('No merchants found. Skipping DepositSeeder.');
            return;
        }

        $banks = [
            'HDFC Bank', 'ICICI Bank', 'State Bank of India', 'Axis Bank',
            'Kotak Mahindra Bank', 'Punjab National Bank', 'Bank of Baroda',
            'Canara Bank', 'Union Bank of India', 'Yes Bank',
        ];

        $ifscPrefixes = ['HDFC', 'ICIC', 'SBIN', 'UTIB', 'KKBK', 'PUNB', 'BARB', 'CNRB', 'UBIN', 'YESB'];

        $modes = ['NEFT', 'RTGS', 'IMPS', 'UPI'];

        $deposits = [];
        $usedSequenceNos = [];

        // Spread at least 100 deposits across all merchants
        $totalDeposits = max(100, $merchants->count() * 8);
        $perMerchant   = (int) ceil($totalDeposits / $merchants->count());

        $now = now();

        foreach ($merchants as $merchant) {
            for ($i = 0; $i < $perMerchant; $i++) {

                // unique alert_sequence_no
                do {
                    $seqNo = strtoupper($faker->bothify('ALT######??'));
                } while (in_array($seqNo, $usedSequenceNos, true));
                $usedSequenceNos[] = $seqNo;

                $txnDate   = $faker->dateTimeBetween('-90 days', 'now');
                $valueDate = Carbon::instance($txnDate)->toDateString();

                $mode   = $faker->randomElement($modes);
                $bank   = $faker->randomElement($banks);
                $ifscIdx = array_search($bank, $banks);
                $ifsc   = $ifscPrefixes[$ifscIdx] . '0' . $faker->numerify('#####');

                // amounts: mix of small (<1000) and large
                $amount = $faker->randomElement([
                    $faker->randomFloat(2, 100, 999),
                    $faker->randomFloat(2, 1000, 50000),
                    $faker->randomFloat(2, 50000, 500000),
                ]);

                $remitterAccount = $faker->numerify('####################');
                $virtualAccount  = $merchant->merchantVirtualAccount?->account_number
                                   ?? $faker->numerify('############');

                $processingStatuses = ['success', 'success', 'success', 'success', 'duplicate', 'technical_reject'];

                $description = "{$mode}/CREDIT/{$faker->bothify('TXN########')}/{$faker->name}";

                $deposits[] = [
                    'user_id'              => $merchant->id,
                    'alert_sequence_no'    => $seqNo,
                    'remitter_name'        => $faker->name,
                    'remitter_account'     => $remitterAccount,
                    'remitter_bank'        => $bank,
                    'user_reference_number'=> strtoupper($faker->bothify('REF#######??')),
                    'virtual_account'      => $virtualAccount,
                    'amount'               => round($amount, 2),
                    'mnemonic_code'        => $faker->randomElement(['CR', 'NEFT', 'RTGS', 'IMPS', 'UPI']),
                    'transaction_date'     => $txnDate->format('Y-m-d H:i:s'),
                    'value_date'           => $valueDate,
                    'ifsc_code'            => $ifsc,
                    'cheque_no'            => null,
                    'transaction_description' => $description,
                    'account_number'       => $virtualAccount,
                    'debit_credit'         => 'CR',
                    'raw_payload'          => json_encode([
                        'source' => 'seeder',
                        'mode'   => $mode,
                        'bank'   => $bank,
                    ]),
                    'processing_status'    => $faker->randomElement($processingStatuses),
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
            }
        }

        // Insert in chunks to avoid memory/packet limits
        foreach (array_chunk($deposits, 50) as $chunk) {
            DB::table('deposits')->insert($chunk);
        }

        $this->command->info('Inserted ' . count($deposits) . ' deposits for ' . $merchants->count() . ' merchant(s).');
    }
}

