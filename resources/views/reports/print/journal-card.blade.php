<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journal #{{ $journal->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Journal Voucher #{{ $journal->id }}</h2>
    <p>Date: {{ $journal->date }} | Branch: {{ $journal->branch->name ?? ('#' . $journal->branch_id) }}</p>
    <p>Description: {{ $journal->description ?: 'N/A' }}</p>
    <p>Reference: {{ $journal->reference_type ?: 'N/A' }}{{ $journal->reference_id ? ' #' . $journal->reference_id : '' }}</p>
    <table>
        <thead><tr><th>Account</th><th>Code</th><th class="right">Debit</th><th class="right">Credit</th></tr></thead>
        <tbody>
            @foreach($journal->entries as $entry)
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
                <th class="right">{{ number_format($journal->entries->sum('debit'), 2) }}</th>
                <th class="right">{{ number_format($journal->entries->sum('credit'), 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <script>window.onload = function(){ window.print(); };</script>
</body>
</html>
