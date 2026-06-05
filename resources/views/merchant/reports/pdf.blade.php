<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Merchant Report</title>
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 9px; }
        .meta { margin-bottom: 12px; }
        .meta h1 { margin: 0 0 4px; font-size: 18px; }
        .meta p { margin: 2px 0; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 4px 5px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; font-weight: 700; }
        .num { text-align: right; }
        .nowrap { white-space: nowrap; }
        .small { font-size: 8px; }
    </style>
</head>
<body>
    <div class="meta">
        <h1>Merchant Deep Report</h1>
        <p>Merchant: {{ $merchantName }}</p>
        <p>Generated At: {{ $generatedAt->format('d M Y, h:i A') }}</p>
        <p>Total Rows: {{ $rows->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap">#</th>
                <th class="nowrap">Initiated At</th>
                <th class="nowrap">Transaction ID</th>
                <th class="nowrap">Beneficiary</th>
                <th class="nowrap">UTR Number</th>
                <th class="num nowrap">Amount</th>
                <th class="num nowrap">Opening Balance</th>
                <th class="num nowrap">Closing Balance</th>
                <th class="nowrap">Mode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td class="nowrap">{{ $loop->iteration }}</td>
                <td class="nowrap">{{ $row->initiated_at ? \Carbon\Carbon::parse($row->initiated_at)->format('d M Y, h:i A') : '-' }}</td>
                <td class="nowrap">{{ $row->transaction_id }}</td>
                <td>
                    <strong>Account Holder: </strong>{{ $row->account_holder }}<br>
                    <strong>Mobile: </strong>{{ $row->mobile }}<br>
                    <strong>Account Number: </strong>{{ $row->account_number }}<br>
                    <strong>Bank Name: </strong>{{ $row->bank_name }}<br>
                    <strong>IFSC Code: </strong>{{ $row->ifsc_code }}
                </td>
                <td class="nowrap">{{ $row->utr ?: 'N/A' }}</td>
                <td class="num nowrap">
                    <strong>Amount: </strong>₹{{ number_format((float) $row->amount, 2) }}<br>
                    <strong>Fee: </strong>₹{{ number_format((float) ($row->fee ?? 0), 2) }}<br>
                    <strong>Total: </strong>₹{{ number_format((float) ($row->total_amount ?? ($row->amount + ($row->fee ?? 0))), 2) }}
                </td>
                <td class="num nowrap">₹{{ number_format((float) ($row->opening_balance ?? 0), 2) }}</td>
                <td class="num nowrap">₹{{ number_format((float) ($row->closing_balance ?? 0), 2) }}</td>
                <td class="nowrap">{{ $row->mode }}</td>
                <td class="nowrap">{{ ucfirst(str_replace('_', ' ', $row->status)) }}{{ $row->refund && $row->refund?->status === 'processed' ? ' (Refunded)' : '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="14">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
