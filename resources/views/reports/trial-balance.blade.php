@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Trial Balance</h1>
        <p class="text-sm text-gray-500 mt-1">Summary of all account balances to ensure debits equal credits.</p>
    </div>
    <a href="{{ route('reports.trial-balance.print', request()->query()) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
        Print
    </a>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden mt-6">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/75 border-b text-gray-500 text-sm uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4 font-medium">Account Code</th>
                <th class="px-6 py-4 font-medium">Account Name</th>
                <th class="px-6 py-4 font-medium text-right">Debit Balance</th>
                <th class="px-6 py-4 font-medium text-right">Credit Balance</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            @php
                $totalDebit = 0;
                $totalCredit = 0;
            @endphp
            @forelse($accounts as $account)
                @php
                    $net = $account->total_debit - $account->total_credit;
                    $debitBalance = $net > 0 ? $net : 0;
                    $creditBalance = $net < 0 ? abs($net) : 0;
                    
                    if($debitBalance > 0 || $creditBalance > 0) {
                        $totalDebit += $debitBalance;
                        $totalCredit += $creditBalance;
                    }
                @endphp
                
                @if($debitBalance > 0 || $creditBalance > 0)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-3 font-semibold text-gray-900">{{ $account->code }}</td>
                    <td class="px-6 py-3">{{ $account->name }} <span class="text-xs text-gray-400 ml-2">({{ ucfirst($account->type) }})</span></td>
                    <td class="px-6 py-3 text-right tabular-nums">{{ number_format($debitBalance, 2) }}</td>
                    <td class="px-6 py-3 text-right tabular-nums">{{ number_format($creditBalance, 2) }}</td>
                </tr>
                @endif
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    <p class="text-base font-medium">No accounts with balances found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
            <tr>
                <td colspan="2" class="px-6 py-5 font-bold text-gray-900 text-right">Total:</td>
                <td class="px-6 py-5 font-bold text-gray-900 text-right tabular-nums {{ $totalDebit !== $totalCredit ? 'text-red-600' : '' }}">{{ number_format($totalDebit, 2) }}</td>
                <td class="px-6 py-5 font-bold text-gray-900 text-right tabular-nums {{ $totalDebit !== $totalCredit ? 'text-red-600' : '' }}">{{ number_format($totalCredit, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

@if($totalDebit !== $totalCredit)
<div class="mt-4 bg-red-50 text-red-700 px-6 py-4 rounded-xl border border-red-200 shadow-sm flex items-center">
    <svg class="h-6 w-6 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <p class="font-medium">Warning: The Trial Balance does not balance. Total debits differ from total credits.</p>
</div>
@endif
@endsection
