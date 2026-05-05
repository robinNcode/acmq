<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Sheet Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Balance Sheet</h2>
    @php $totalAssets = 0; $totalLiabilities = 0; $totalEquity = 0; @endphp
    <h4>Assets</h4>
    <table>
        <thead><tr><th>Account</th><th class="right">Amount</th></tr></thead>
        <tbody>
            @foreach($assetAccounts as $account)
                @php $balance = $account->total_debit - $account->total_credit; if($balance != 0) { $totalAssets += $balance; } @endphp
                @if($balance != 0)<tr><td>{{ $account->name }}</td><td class="right">{{ number_format($balance, 2) }}</td></tr>@endif
            @endforeach
        </tbody>
    </table>
    <h4>Liabilities</h4>
    <table>
        <thead><tr><th>Account</th><th class="right">Amount</th></tr></thead>
        <tbody>
            @foreach($liabilityAccounts as $account)
                @php $balance = $account->total_credit - $account->total_debit; if($balance != 0) { $totalLiabilities += $balance; } @endphp
                @if($balance != 0)<tr><td>{{ $account->name }}</td><td class="right">{{ number_format($balance, 2) }}</td></tr>@endif
            @endforeach
        </tbody>
    </table>
    <h4>Equity</h4>
    <table>
        <thead><tr><th>Account</th><th class="right">Amount</th></tr></thead>
        <tbody>
            @foreach($equityAccounts as $account)
                @php $balance = $account->total_credit - $account->total_debit; if($balance != 0) { $totalEquity += $balance; } @endphp
                @if($balance != 0)<tr><td>{{ $account->name }}</td><td class="right">{{ number_format($balance, 2) }}</td></tr>@endif
            @endforeach
            <tr><td>Retained Earnings (Net {{ $netIncome >= 0 ? 'Income' : 'Loss' }})</td><td class="right">{{ number_format($netIncome, 2) }}</td></tr>
        </tbody>
    </table>
    <h3>Total Assets: {{ number_format($totalAssets, 2) }}</h3>
    <h3>Total Liabilities & Equity: {{ number_format($totalLiabilities + $totalEquity + $netIncome, 2) }}</h3>
    <script>window.onload = function(){ window.print(); };</script>
</body>
</html>
