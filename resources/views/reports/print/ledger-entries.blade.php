<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General Ledger Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>General Ledger</h2>
    <table>
        <thead>
            <tr><th>Date</th><th>Description</th><th>Account</th><th class="right">Debit</th><th class="right">Credit</th></tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                <tr>
                    <td>{{ $entry->journal->date ?? 'N/A' }}</td>
                    <td>{{ $entry->journal->description ?? 'N/A' }}</td>
                    <td>{{ $entry->account->name ?? 'N/A' }} ({{ $entry->account->code ?? 'N/A' }})</td>
                    <td class="right">{{ number_format($entry->debit, 2) }}</td>
                    <td class="right">{{ number_format($entry->credit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>window.onload = function(){ window.print(); };</script>
</body>
</html>
