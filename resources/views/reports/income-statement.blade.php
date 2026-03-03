@extends('layouts.app')

@section('title', 'Income Statement (P&L)')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Income Statement</h1>
    <p class="text-sm text-gray-500 mt-1">Profit and Loss over the selected period.</p>
</div>

@php
    $totalIncome = 0;
    $totalExpense = 0;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
    <!-- Revenue Section -->
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
        <div class="bg-green-50 border-b border-green-100 px-6 py-4">
            <h2 class="text-xl font-bold text-green-800">Income (Revenue)</h2>
        </div>
        <table class="w-full text-left border-collapse">
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @foreach($incomeAccounts as $account)
                    @php
                        // Revenue increases with credit
                        $balance = $account->total_credit - $account->total_debit;
                        if($balance != 0) $totalIncome += $balance;
                    @endphp
                    @if($balance != 0)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">{{ $account->name }}</td>
                        <td class="px-6 py-3 text-right tabular-nums">{{ number_format($balance, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td class="px-6 py-4 font-bold text-gray-900 text-right">Total Income:</td>
                    <td class="px-6 py-4 font-bold text-green-600 text-right tabular-nums">{{ number_format($totalIncome, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Expenses Section -->
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
        <div class="bg-red-50 border-b border-red-100 px-6 py-4">
            <h2 class="text-xl font-bold text-red-800">Expenses</h2>
        </div>
        <table class="w-full text-left border-collapse">
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @foreach($expenseAccounts as $account)
                    @php
                        // Expenses increase with debit
                        $balance = $account->total_debit - $account->total_credit;
                        if($balance != 0) $totalExpense += $balance;
                    @endphp
                    @if($balance != 0)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">{{ $account->name }}</td>
                        <td class="px-6 py-3 text-right tabular-nums">{{ number_format($balance, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td class="px-6 py-4 font-bold text-gray-900 text-right">Total Expenses:</td>
                    <td class="px-6 py-4 font-bold text-red-600 text-right tabular-nums">{{ number_format($totalExpense, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@php
    $netIncome = $totalIncome - $totalExpense;
@endphp

<div class="bg-white border-2 {{ $netIncome >= 0 ? 'border-green-200 bg-green-50/30' : 'border-red-200 bg-red-50/30' }} rounded-xl shadow-sm p-6 flex justify-between items-center mt-8">
    <h2 class="text-2xl font-black text-gray-900">Net {{ $netIncome >= 0 ? 'Income' : 'Loss' }}</h2>
    <div class="text-3xl font-black tabular-nums {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-600' }}">
        {{ number_format(abs($netIncome), 2) }}
    </div>
</div>
@endsection
