<!DOCTYPE html>
<html lang="en" style="background:#f6f8fc;">
<head>
    <meta charset="UTF-8">
    <title>Invoice Generated - MakeMyPayment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        ph
    </style>
</head>
<body>
    <div class="container">
        <img class="logo" src="https://makemypayment.in/makemypayment-logo.png" alt="MakeMyPayment" height="56">
        
        <div class="heading">Invoice Generated Successfully</div>
        <div class="subheading">
            Hello {{ $invoice->user->full_name }},<br>
            Your invoice has been generated and is ready for download. Please find the details below.
        </div>

        <div class="invoice-box">
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            <div class="invoice-details">
                <strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}<br>
                @if($invoice->due_date)
                <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}<br>
                @endif
                <strong>Total Amount:</strong> ₹{{ number_format($invoice->total, 2) }}<br>
                <strong>Status:</strong> <span style="color: {{ $invoice->status === 'paid' ? '#10b981' : '#f59e0b' }};">{{ ucfirst($invoice->status) }}</span>
            </div>
        </div>

        <div class="attachment-info">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #10b981;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <p style="color: #065f46; font-weight: 600; margin-bottom: 4px;">📎 Invoice Attached</p>
            <p style="color: #047857; font-size: 0.85rem;">Please find your invoice attached to this email.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('merchant.invoices') }}" class="btn">View in Dashboard</a>
        </div>

        <div class="info-box">
            <p><strong>💡 Quick Tip:</strong> You can also download this invoice anytime from your dashboard under the <a href="{{ route('merchant.invoices') }}" class="link">Invoices</a> section.</p>
        </div>

        <div style="color:#4f5e6b; font-size:0.95rem; line-height: 1.6; margin-top: 24px;">
            If you have any questions regarding this invoice, please don't hesitate to contact our support team at 
            <a href="mailto:support@makemypayment.in" class="link">support@makemypayment.in</a>
        </div>

        <hr class="divider">

        <div class="footer">
            &copy; {{ date('Y') }} MakeMyPayment. All rights reserved.<br>
            <span style="color:#0ea5e9;">India's trusted payment solution.</span><br>
            <div style="margin-top: 16px;">
                <a href="{{ route('merchant.dashboard') }}" class="link" style="font-size: 0.85rem;">Dashboard</a> • 
                <a href="{{ route('merchant.invoices') }}" class="link" style="font-size: 0.85rem;">Invoices</a> • 
                <a href="mailto:support@makemypayment.in" class="link" style="font-size: 0.85rem;">Support</a>
            </div>
        </div>
    </div>
</body>
</html>
