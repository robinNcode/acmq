@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Balance Sheet</h1>
        <p class="text-sm text-gray-500 mt-1">Snapshot of balances: Assets = Liabilities + Equity.</p>
    </div>
    <a href="{{ route('reports.balance-sheet.print', request()->query()) }}" target="_blank" title="Print report" class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18h12v4H6v-4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 14H4a2 2 0 01-2-2v-1a2 2 0 012-2h16a2 2 0 012 2v1a2 2 0 01-2 2h-2"/></svg>
    </a>
</div>

@php
    $totalAssets = 0;
    $totalLiabilities = 0;
    $totalEquity = 0;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
    
    <!-- Assets (Left Side) -->
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden flex flex-col">
        <div class="bg-blue-50 border-b border-blue-100 px-6 py-4">
            <h2 class="text-xl font-bold text-blue-800">Assets</h2>
        </div>
        <table class="w-full text-left border-collapse flex-1">
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @foreach($assetAccounts as $account)
                    @php
                        // Assets increase with debit
                        $balance = $account->total_debit - $account->total_credit;
                        if($balance != 0) $totalAssets += $balance;
                    @endphp
                    @if($balance != 0)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">{{ $account->name }}</td>
                        <td class="px-6 py-3 text-right tabular-nums">{{ number_format($balance, 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="bg-gray-50 border-t-2 border-gray-200 px-6 py-4 flex justify-between items-center mt-auto">
            <span class="font-bold text-gray-900">Total Assets:</span>
            <span class="font-bold text-blue-600 text-lg tabular-nums">{{ number_format($totalAssets, 2) }}</span>
        </div>
    </div>

    <!-- Liabilities & Equity (Right Side) -->
    <div class="flex flex-col gap-8">
        <!-- Liabilities Section -->
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-4">
                <h2 class="text-xl font-bold text-indigo-800">Liabilities</h2>
            </div>
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($liabilityAccounts as $account)
                        @php
                            // Liabilities increase with credit
                            $balance = $account->total_credit - $account->total_debit;
                            if($balance != 0) $totalLiabilities += $balance;
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
                        <td class="px-6 py-3 font-bold text-gray-900 text-right">Total Liabilities:</td>
                        <td class="px-6 py-3 font-bold text-indigo-600 text-right tabular-nums">{{ number_format($totalLiabilities, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Equity Section -->
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="bg-purple-50 border-b border-purple-100 px-6 py-4">
                <h2 class="text-xl font-bold text-purple-800">Equity</h2>
            </div>
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($equityAccounts as $account)
                        @php
                            // Equity increases with credit
                            $balance = $account->total_credit - $account->total_debit;
                            if($balance != 0) $totalEquity += $balance;
                        @endphp
                        @if($balance != 0)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3">{{ $account->name }}</td>
                            <td class="px-6 py-3 text-right tabular-nums">{{ number_format($balance, 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    <!-- Add Net Income dynamically to Equity -->
                    <tr class="hover:bg-gray-50/50 transition-colors bg-green-50/50">
                        <td class="px-6 py-3 font-medium text-green-800">Retained Earnings (Net {{ $netIncome >= 0 ? 'Income' : 'Loss' }})</td>
                        <td class="px-6 py-3 text-right tabular-nums text-green-700 font-medium">{{ number_format($netIncome, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    @php $totalEquityIncNet = $totalEquity + $netIncome; @endphp
                    <tr>
                        <td class="px-6 py-3 font-bold text-gray-900 text-right">Total Equity:</td>
                        <td class="px-6 py-3 font-bold text-purple-600 text-right tabular-nums">{{ number_format($totalEquityIncNet, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

</div>

@php $totalLibsAndEquity = $totalLiabilities + $totalEquity + $netIncome; @endphp

<!-- Bottom Total Balancing -->
<div class="bg-white border-2 {{ round($totalAssets, 2) === round($totalLibsAndEquity, 2) ? 'border-green-200 bg-green-50/30' : 'border-red-200 bg-red-50/30' }} rounded-xl shadow-sm p-6 flex justify-between items-center mt-4">
    <h2 class="text-2xl font-black text-gray-900">Total Liabilities & Equity</h2>
    <div class="text-3xl font-black tabular-nums {{ round($totalAssets, 2) === round($totalLibsAndEquity, 2) ? 'text-green-600' : 'text-red-600' }}">
        {{ number_format($totalLibsAndEquity, 2) }}
    </div>
</div>

@if(round($totalAssets, 2) !== round($totalLibsAndEquity, 2))
<div class="mt-4 bg-red-50 text-red-700 px-6 py-4 rounded-xl border border-red-200 shadow-sm flex items-center">
    <svg class="h-6 w-6 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <p class="font-medium">Warning: The Balance Sheet does not balance! Difference: {{ number_format(abs($totalAssets - $totalLibsAndEquity), 2) }}</p>
</div>
@endif
@endsection
