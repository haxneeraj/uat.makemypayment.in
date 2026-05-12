<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Merchant Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        .meta { margin-bottom: 16px; }
        .meta h1 { margin: 0 0 4px; font-size: 20px; }
        .meta p { margin: 2px 0; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; font-weight: 700; }
        .num { text-align: right; }
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
                <th>Type</th>
                <th>Reference</th>
                <th>Name</th>
                <th>Bank</th>
                <th>Account</th>
                <th class="num">Amount</th>
                <th class="num">Charges</th>
                <th class="num">Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td>{{ ucfirst($row->entry_type) }}</td>
                <td>{{ $row->reference_no }}</td>
                <td>{{ $row->party_name }}</td>
                <td>{{ $row->bank_name }}</td>
                <td>{{ $row->account_no }}</td>
                <td class="num">{{ number_format((float) $row->amount, 2) }}</td>
                <td class="num">{{ number_format((float) $row->charges, 2) }}</td>
                <td class="num">{{ number_format((float) $row->total_amount, 2) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $row->source_status)) }}</td>
                <td>{{ $row->txn_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
