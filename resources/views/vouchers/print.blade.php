<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher #{{ $voucher->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        h1 { margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; font-size: 13px; }
        th { background: #f9fafb; text-align: left; }
        .right { text-align: right; }
        .meta { margin-bottom: 8px; font-size: 13px; color: #374151; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print</button>
    <h1>{{ strtoupper($voucher->reference_type) }} Voucher #{{ $voucher->id }}</h1>
    <div class="meta">Date: {{ $voucher->date }} | Branch: {{ $voucher->branch->name ?? ('#' . $voucher->branch_id) }}</div>
    <div class="meta">Description: {{ $voucher->description ?: 'N/A' }}</div>
    <table>
        <thead>
            <tr>
                <th>Account</th>
                <th>Code</th>
                <th class="right">Debit</th>
                <th class="right">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voucher->entries as $entry)
                <tr>
                    <td>{{ $entry->account->name ?? 'N/A' }}</td>
                    <td>{{ $entry->account->code ?? 'N/A' }}</td>
                    <td class="right">{{ number_format($entry->debit, 2) }}</td>
                    <td class="right">{{ number_format($entry->credit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="right">Total</th>
                <th class="right">{{ number_format($voucher->entries->sum('debit'), 2) }}</th>
                <th class="right">{{ number_format($voucher->entries->sum('credit'), 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <script>window.onload = function () { window.print(); };</script>
</body>
</html>
