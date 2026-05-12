<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .container { padding: 20px; }
        
        /* Header Table */
        .header-table { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #1a237e; padding-bottom: 10px; }
        .header-table td { vertical-align: top; }
        .company-name { font-size: 18px; font-weight: bold; color: #1a237e; margin-bottom: 5px; }
        .company-details { font-size: 9px; line-height: 1.6; }
        .invoice-title { text-align: right; font-size: 24px; font-weight: bold; color: #1a237e; }
        .invoice-meta { text-align: right; font-size: 10px; margin-top: 5px; line-height: 1.6; }
        
        /* Address Table */
        .address-table { width: 100%; margin: 20px 0; }
        .address-table td { width: 50%; vertical-align: top; padding: 10px; }
        .address-title { font-weight: bold; margin-bottom: 5px; font-size: 10px; }
        .address-content { font-size: 9px; line-height: 1.6; }
        
        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background-color: #f0f0f0; padding: 8px; text-align: left; font-size: 10px; border: 1px solid #ddd; }
        .items-table td { padding: 8px; border: 1px solid #ddd; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Totals Table */
        .totals-table { width: 100%; margin-top: 20px; }
        .totals-table td { padding: 3px 0; }
        .totals-label { text-align: right; padding-right: 10px; font-weight: bold; width: 75%; }
        .totals-value { text-align: right; width: 25%; }
        .grand-total-row td { font-size: 14px; font-weight: bold; border-top: 2px solid #000; padding-top: 5px; }
        
        .amount-words { margin-top: 15px; font-style: italic; font-size: 10px; line-height: 1.6; }
        .note { margin-top: 15px; font-size: 9px; color: #666; font-style: italic; line-height: 1.6; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 60%;">
                    @php
                        $logoPath = public_path('makemypayment-logo.png');
                        if(file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = 'data:image/png;base64,' . $logoData;
                        } else {
                            $logoSrc = '';
                        }
                    @endphp
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" alt="MakeMyPayment" style="height: 80px;">
                    @else
                        <span style="font-size: 18px; font-weight: bold; color: #1a237e;">MakeMyPayment</span>
                    @endif
                    <div class="company-details">
                        <strong>M.M.P Fintech Payment Solution Pvt Ltd</strong><br>
                        SHOP NO. A/409, PUSHPAK CORNER, NR.VISHWA KARMA CHAWK<br>
                        OPP.NAVYUG SCHOOL, NARODA<br>
                        AHMEDABAD, Gujarat, 382330, India<br>

                        GSTIN: 24AAJCC5995N1ZW<br>
                        <br>
                        Email: support@makemypayment.in<br>
                        Phone: +91 6354409951
                    </div>
                </td>
                <td style="width: 40%;">
                    <div class="invoice-title">TAX INVOICE</div>
                    <div class="invoice-meta">
                        Invoice #: <strong>{{ $invoice->invoice_number }}</strong><br>
                        Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}<br>
                        @if($invoice->due_date)
                        Due Date: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Addresses -->
        <table class="address-table" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="address-title">Bill To</div>
                    <div class="address-content">
                        <strong>{{ $invoice->user->merchantKyc->business_name ?? $invoice->user->full_name }}</strong><br>
                        {{ $invoice->billing_address_line_1 }}<br>
                        @if($invoice->billing_address_line_2)
                        {{ $invoice->billing_address_line_2 }}<br>
                        @endif
                        {{ $invoice->billing_city }}, {{ $invoice->billing_state }} {{ $invoice->billing_zip }}
                    </div>
                </td>
                <td>
                    <div class="address-title">Ship To</div>
                    <div class="address-content">
                        {{ $invoice->shipping_address_line_1 ?? $invoice->billing_address_line_1 }}<br>
                        @if($invoice->shipping_address_line_2 ?? $invoice->billing_address_line_2)
                        {{ $invoice->shipping_address_line_2 ?? $invoice->billing_address_line_2 }}<br>
                        @endif
                        {{ $invoice->shipping_city ?? $invoice->billing_city }}, 
                        {{ $invoice->shipping_state ?? $invoice->billing_state }} 
                        {{ $invoice->shipping_zip ?? $invoice->billing_zip }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">S.No</th>
                    <th style="width: 35%;">Item & Description</th>
                    <th style="width: 10%;" class="text-center">HSN/SAC</th>
                    <th style="width: 8%;" class="text-center">Qty</th>
                    <th style="width: 12%;" class="text-right">Rate</th>
                    <th style="width: 10%;" class="text-center">GST</th>
                    <th style="width: 10%;" class="text-right">GST Amt</th>
                    <th style="width: 10%;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->hsn_sac_code ?? '-' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ number_format($item->gst_rate, 2) }}%</td>
                    <td class="text-right">₹{{ number_format($item->gst_amount, 2) }}</td>
                    <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table" cellspacing="0" cellpadding="0">
            <tr>
                <td class="totals-label">Sub Total:</td>
                <td class="totals-value">₹{{ number_format($invoice->base_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="totals-label">IGST (18%):</td>
                <td class="totals-value">₹{{ number_format($invoice->gst_amount, 2) }}</td>
            </tr>
            <tr class="grand-total-row">
                <td class="totals-label">Total:</td>
                <td class="totals-value">₹{{ number_format($invoice->total, 2) }}</td>
            </tr>
            <tr>
                <td class="totals-label">Payment Mode:</td>
                <td class="totals-value">{{ $invoice->status === 'paid' ? 'PAID' : 'PENDING' }}</td>
            </tr>
            <tr>
                <td class="totals-label">Balance Due:</td>
                <td class="totals-value">₹{{ $invoice->status === 'paid' ? '0.00' : number_format($invoice->total, 2) }}</td>
            </tr>
        </table>

        <!-- Amount in Words -->
        <div class="amount-words">
            <strong>Total in Words:</strong><br>
            {{ $invoice->total_in_words }}
        </div>

        <!-- Note -->
        <div class="note">
            <strong>Note:</strong><br>
            This is a computer generated invoice - No signature required<br>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>Authorized Signature</strong><br><br>
            &copy; {{ date('Y') }} MMP Fintech Payment Solutions Pvt. Ltd. All rights reserved.
        </div>
    </div>
</body>
</html>
