<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Income Statement Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Income Statement</h2>
    @php $totalIncome = 0; $totalExpense = 0; @endphp
    <h4>Income</h4>
    <table>
        <thead><tr><th>Account</th><th class="right">Amount</th></tr></thead>
        <tbody>
        @foreach($incomeAccounts as $account)
            @php $balance = $account->total_credit - $account->total_debit; if($balance != 0) { $totalIncome += $balance; } @endphp
            @if($balance != 0)<tr><td>{{ $account->name }}</td><td class="right">{{ number_format($balance, 2) }}</td></tr>@endif
        @endforeach
        </tbody>
    </table>
    <h4>Expenses</h4>
    <table>
        <thead><tr><th>Account</th><th class="right">Amount</th></tr></thead>
        <tbody>
        @foreach($expenseAccounts as $account)
            @php $balance = $account->total_debit - $account->total_credit; if($balance != 0) { $totalExpense += $balance; } @endphp
            @if($balance != 0)<tr><td>{{ $account->name }}</td><td class="right">{{ number_format($balance, 2) }}</td></tr>@endif
        @endforeach
        </tbody>
    </table>
    <h3>Net {{ ($totalIncome - $totalExpense) >= 0 ? 'Income' : 'Loss' }}: {{ number_format(abs($totalIncome - $totalExpense), 2) }}</h3>
    <script>window.onload = function(){ window.print(); };</script>
</body>
</html>
