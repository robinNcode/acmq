<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trial Balance Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Trial Balance</h2>
    @php $totalDebit = 0; $totalCredit = 0; @endphp
    <table>
        <thead><tr><th>Code</th><th>Account</th><th class="right">Debit</th><th class="right">Credit</th></tr></thead>
        <tbody>
            @foreach($accounts as $account)
                @php
                    $net = $account->total_debit - $account->total_credit;
                    $debitBalance = $net > 0 ? $net : 0;
                    $creditBalance = $net < 0 ? abs($net) : 0;
                    if($debitBalance > 0 || $creditBalance > 0) { $totalDebit += $debitBalance; $totalCredit += $creditBalance; }
                @endphp
                @if($debitBalance > 0 || $creditBalance > 0)
                    <tr>
                        <td>{{ $account->code }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="right">{{ number_format($debitBalance, 2) }}</td>
                        <td class="right">{{ number_format($creditBalance, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="2" class="right">Total</th><th class="right">{{ number_format($totalDebit, 2) }}</th><th class="right">{{ number_format($totalCredit, 2) }}</th></tr>
        </tfoot>
    </table>
    <script>window.onload = function(){ window.print(); };</script>
</body>
</html>
