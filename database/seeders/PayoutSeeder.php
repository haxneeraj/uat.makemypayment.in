<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id');

        if ($userIds->isEmpty()) {
            $this->command?->warn('PayoutSeeder skipped: no users found.');

            return;
        }

        // Delete all existing payouts
        Schema::disableForeignKeyConstraints();
        DB::table('payouts')->truncate();
        Schema::enableForeignKeyConstraints();

        $availableColumns = array_flip(Schema::getColumnListing('payouts'));
        $fromDate = Carbon::create(2026, 2, 1, 0, 0, 0);
        $toDate = now();
        $rows = [];

        foreach ($userIds as $userId) {
            $cursor = $fromDate->copy();
            $baseAmount = 1000 + ($userId % 7) * 250;

            while ($cursor->lte($toDate)) {
                $amount = (float) $baseAmount;
                $fee = round($amount * 0.01, 2);
                $total = $amount + $fee;
                $accountNumber = str_pad((string) ($userId * 17 + 1234567890), 10, '0', STR_PAD_LEFT);
                $mobile = str_pad((string) (9000000000 + ($userId % 999999999)), 10, '0', STR_PAD_LEFT);
                $initiatedAt = $cursor->copy()->setTime(10, 0, 0);
                $processedAt = $cursor->copy()->setTime(10, 5, 0);
                $stamp = $cursor->format('Ymd');

                $payload = [
                    'user_id' => $userId,
                    'batch_id' => null,
                    'transaction_id' => sprintf('TXN%sU%s', $stamp, $userId),
                    'sprintnxt_txn_id' => sprintf('SPR%sU%s', $stamp, $userId),
                    'txn_status' => 1,
                    'sprintnxt_logger_id' => sprintf('LOG%sU%s', $stamp, $userId),
                    'utr' => sprintf('UTR%sU%s', $stamp, $userId),
                    'initiated_at' => $initiatedAt,
                    'processed_at' => $processedAt,
                    'account_holder' => sprintf('User %s', $userId),
                    'account_number' => $accountNumber,
                    'ifsc_code' => 'IFSC0001234',
                    'branch_code' => 'BR001',
                    'bank_name' => 'Bank of Test',
                    'branch_name' => 'Test Branch',
                    'mobile' => $mobile,
                    'email' => sprintf('user%s@example.com', $userId),
                    'city' => 'Test City',
                    'state' => 'Test State',
                    'pincode' => '123456',
                    'beneficiary_address' => '123 Test Street',
                    'amount' => $amount,
                    'fee' => $fee,
                    'total_amount' => $total,
                    'opening_balance' => 500000.00,
                    'closing_balance' => 500000.00 - $total,
                    'mode' => 'imps',
                    'status' => 'success',
                    'remarks' => 'Auto seeded payout',
                    'purpose' => 'Payout seed data',
                    'narration' => 'Daily payout seeded',
                    'initiated_from' => 'api',
                    'created_at' => $initiatedAt,
                    'updated_at' => $processedAt,
                ];

                $rows[] = array_intersect_key($payload, $availableColumns);
                $cursor->addDay();
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('payouts')->insert($chunk);
        }

        // Log command information
        $this->command->info("PayoutSeeder executed successfully. {$userIds->count()} users ke liye Feb 1, 2026 se ab tak payouts create ho gaye.");
    }
}
