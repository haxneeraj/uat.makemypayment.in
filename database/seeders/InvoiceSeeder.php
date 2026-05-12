<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'cybler.tk@gmail.com')->first();

        if (!$user) {
            $this->command->error('User with email cybler.tk@gmail.com not found!');
            return;
        }

        $this->command->info('Creating invoices for ' . $user->full_name);

        // Create 20 sample invoices
        for ($i = 1; $i <= 1; $i++) {
            $invoiceDate = Carbon::now()->subDays(rand(1, 30));
            $dueDate = $invoiceDate->copy()->addDays(15);
            
            $baseAmount = rand(5000, 50000);
            $gstAmount = $baseAmount * 0.18; // 18% GST
            $total = $baseAmount + $gstAmount;

            $invoice = Invoice::create([
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'base_amount' => $baseAmount,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'total_in_words' => $this->numberToWords($total) . ' Only',
                'billing_address_line_1' => $user->merchantKyc->business_address ?? '123, Business Park',
                'billing_address_line_2' => 'Near Tech Hub',
                'billing_city' => $user->merchantKyc->city ?? 'Mumbai',
                'billing_state' => $user->merchantKyc->state ?? 'Maharashtra',
                'billing_zip' => $user->merchantKyc->pin_code ?? '400001',
                'shipping_address_line_1' => $user->merchantKyc->business_address ?? '123, Business Park',
                'shipping_address_line_2' => 'Near Tech Hub',
                'shipping_city' => $user->merchantKyc->city ?? 'Mumbai',
                'shipping_state' => $user->merchantKyc->state ?? 'Maharashtra',
                'shipping_zip' => $user->merchantKyc->pin_code ?? '400001',
                'status' => $this->getRandomStatus(),
            ]);

            // Create invoice items (2-5 items per invoice)
            $itemCount = rand(2, 5);
            for ($j = 1; $j <= $itemCount; $j++) {
                $quantity = rand(1, 10);
                $unitPrice = rand(500, 5000);
                $itemBaseAmount = $quantity * $unitPrice;
                $itemGstAmount = $itemBaseAmount * 0.18;
                $itemTotal = $itemBaseAmount + $itemGstAmount;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $this->getRandomService($j),
                    'hsn_sac_code' => '998' . rand(100, 999),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'gst_type' => 'igst',
                    'gst_rate' => 18.00,
                    'base_amount' => $itemBaseAmount,
                    'gst_amount' => $itemGstAmount,
                    'total' => $itemTotal,
                ]);
            }

            $this->command->info('Created invoice: ' . $invoice->invoice_number);
        }

        $this->command->info('✓ Successfully created 10 invoices');
    }

    private function getRandomStatus()
    {
        $statuses = ['pending', 'paid', 'overdue'];
        return $statuses[array_rand($statuses)];
    }

    private function getRandomService($index)
    {
        $services = [
            'Payment Gateway Services',
            'Transaction Processing Fee',
            'API Integration Services',
            'Monthly Subscription Fee',
            'Payout Processing Charges',
            'Settlement Services',
            'Technical Support',
            'Custom Development',
            'Consultation Services',
            'Platform Maintenance',
        ];
        return $services[$index % count($services)];
    }

    private function numberToWords($number)
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

        $number = number_format($number, 2, '.', '');
        list($rupees, $paise) = explode('.', $number);
        
        $words = '';
        
        if ($rupees == 0) {
            $words = 'Zero Rupees';
        } else {
            $crore = (int)($rupees / 10000000);
            $rupees = $rupees % 10000000;
            $lakh = (int)($rupees / 100000);
            $rupees = $rupees % 100000;
            $thousand = (int)($rupees / 1000);
            $rupees = $rupees % 1000;
            $hundred = (int)($rupees / 100);
            $rupees = $rupees % 100;

            if ($crore) {
                $words .= $this->convertNumber($crore) . ' Crore ';
            }
            if ($lakh) {
                $words .= $this->convertNumber($lakh) . ' Lakh ';
            }
            if ($thousand) {
                $words .= $this->convertNumber($thousand) . ' Thousand ';
            }
            if ($hundred) {
                $words .= $this->convertNumber($hundred) . ' Hundred ';
            }
            if ($rupees) {
                $words .= $this->convertNumber($rupees);
            }
            $words .= ' Rupees';
        }

        if ($paise > 0) {
            $words .= ' and ' . $this->convertNumber((int)$paise) . ' Paise';
        }

        return $words;
    }

    private function convertNumber($number)
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

        if ($number < 10) {
            return $ones[$number];
        } elseif ($number < 20) {
            return $teens[$number - 10];
        } elseif ($number < 100) {
            return $tens[(int)($number / 10)] . ' ' . $ones[$number % 10];
        }
        return '';
    }
}
