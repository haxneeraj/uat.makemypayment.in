<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Merchant Inward Report</title>
    <style>
        @page { margin: 18px; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 10px; }
        .meta { margin-bottom: 12px; }
        .meta h1 { margin: 0 0 4px; font-size: 18px; }
        .meta p { margin: 2px 0; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 5px 6px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; font-weight: 700; }
        .num { text-align: right; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="meta">
        <h1>Merchant Inward Report</h1>
        <p>Merchant: {{ $merchantName }}</p>
        <p>Generated At: {{ $generatedAt->format('d M Y, h:i A') }}</p>
        <p>Total Rows: {{ $rows->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nowrap">Transaction ID</th>
                <th class="nowrap">Virtual Account</th>
                <th class="num nowrap">Amount</th>
                <th class="nowrap">Transaction Date</th>
                <th>Description</th>
                <th class="nowrap">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td class="nowrap">{{ $row->alert_sequence_no }}</td>
                <td class="nowrap">{{ $row->virtual_account ?? $row->account_number }}</td>
                <td class="num nowrap">{{ number_format((float) $row->amount, 2) }}</td>
                <td class="nowrap">{{ $row->transaction_date ? \Carbon\Carbon::parse($row->transaction_date)->format('d M Y, h:i A') : '-' }}</td>
                <td>{{ $row->transaction_description ?? '-' }}</td>
                <td class="nowrap">{{ ucfirst(str_replace('_', ' ', $row->processing_status)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
