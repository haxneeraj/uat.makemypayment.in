<?php

namespace Database\Seeders;

use App\Models\Payout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();

        if (empty($users)) {
            $this->command?->warn('No users found. Please seed users first, then run PayoutSeeder.');
            return;
        }

        $faker = fake();
        $startDate = Carbon::create(2026, 4, 1)->startOfDay();
        $endDate = now()->endOfDay();

        $cities = ['Delhi', 'Mumbai', 'Bengaluru', 'Pune', 'Chennai', 'Jaipur', 'Ahmedabad'];
        $states = ['Delhi', 'Maharashtra', 'Karnataka', 'Rajasthan', 'Gujarat', 'Tamil Nadu'];
        $banks = [
            ['bank' => 'HDFC Bank', 'ifsc' => 'HDFC0000123', 'branch' => 'Karol Bagh', 'branch_code' => 'KB001'],
            ['bank' => 'ICICI Bank', 'ifsc' => 'ICIC0000456', 'branch' => 'Andheri East', 'branch_code' => 'AE002'],
            ['bank' => 'Axis Bank', 'ifsc' => 'UTIB0000789', 'branch' => 'Sector 18', 'branch_code' => 'S1803'],
            ['bank' => 'State Bank of India', 'ifsc' => 'SBIN0000333', 'branch' => 'Connaught Place', 'branch_code' => 'CP004'],
            ['bank' => 'Kotak Mahindra Bank', 'ifsc' => 'KKBK0000555', 'branch' => 'MG Road', 'branch_code' => 'MG005'],
        ];
        $purposes = ['Vendor Payment', 'Salary Disbursal', 'Refund', 'Commission Payout', 'Utility Settlement'];
        $remarks = ['Priority transfer', 'Monthly cycle', 'Scheduled payout', 'Ops approved', 'As discussed'];
        $narrations = ['Invoice settlement', 'Merchant payout', 'Business transfer', 'Wallet withdrawal', 'Partner payment'];
        $modes = ['imps', 'neft', 'rtgs', 'a2a'];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $transactionsPerDay = random_int(20, 30);

            for ($i = 1; $i <= $transactionsPerDay; $i++) {
                $bank = $banks[array_rand($banks)];
                $amount = (float) random_int(1000, 250000);
                $fee = $amount <= 1000 ? 5.00 : round(($amount * 0.75) / 100, 2);
                $totalAmount = round($amount + $fee, 2);
                $status = $faker->randomElement(['initiated', 'pending', 'send_to_bank', 'success', 'failed', 'processed']);
                $txnStatus = match ($status) {
                    'initiated' => 0,
                    'pending' => 2,
                    'send_to_bank' => 3,
                    'failed' => 4,
                    'processed' => 6,
                    default => 1,
                };

                $createdAt = $date->copy()->setTime(
                    random_int(0, 23),
                    random_int(0, 59),
                    random_int(0, 59)
                );
                $processedAt = in_array($status, ['success', 'failed', 'processed'], true)
                    ? $createdAt->copy()->addMinutes(random_int(5, 240))
                    : null;

                Payout::create([
                    'user_id'             => $users[array_rand($users)],
                    'transaction_id'      => 'TRX' . $date->format('Ymd') . strtoupper(Str::random(10)) . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                    'account_holder'      => $faker->name(),
                    'account_number'      => (string) random_int(1000000000, 9999999999),
                    'ifsc_code'           => $bank['ifsc'],
                    'branch_code'         => $bank['branch_code'],
                    'bank_name'           => $bank['bank'],
                    'branch_name'         => $bank['branch'],
                    'mobile'              => '9' . (string) random_int(100000000, 999999999),
                    'email'               => $faker->safeEmail(),
                    'city'                => $cities[array_rand($cities)],
                    'state'               => $states[array_rand($states)],
                    'pincode'             => (string) random_int(100000, 999999),
                    'beneficiary_address' => $faker->streetAddress(),
                    'amount'              => $amount,
                    'fee'                 => $fee,
                    'total_amount'        => $totalAmount,
                    'mode'                => $modes[array_rand($modes)],
                    'status'              => $status,
                    'purpose'             => $purposes[array_rand($purposes)],
                    'remarks'             => $remarks[array_rand($remarks)],
                    'narration'           => $narrations[array_rand($narrations)],
                    'sprintnxt_txn_id'    => strtoupper(Str::random(12)),
                    'txn_status'          => $txnStatus,
                    'sprintnxt_logger_id' => 'LOG' . strtoupper(Str::random(8)),
                    'utr'                 => in_array($status, ['success', 'processed'], true) ? 'UTR' . random_int(100000000000, 999999999999) : null,
                    'initiated_at'        => $createdAt,
                    'processed_at'        => $processedAt,
                    'created_at'          => $createdAt,
                    'updated_at'          => $processedAt ?? $createdAt,
                ]);
            }
        }

        $this->command?->info('PayoutSeeder completed from 2026-04-01 to today with 20-30 transactions per day.');
    }
}
