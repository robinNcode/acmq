@extends('layouts.app')

@section('title', 'Voucher #' . $voucher->id)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ strtoupper($voucher->reference_type) }} Voucher #{{ $voucher->id }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $voucher->date }} | {{ $voucher->branch->name ?? ('#' . $voucher->branch_id) }}</p>
    </div>
    <a target="_blank" href="{{ route('vouchers.print', $voucher->id) }}" title="Print voucher" class="p-2 rounded-lg bg-green-600 text-white">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18h12v4H6v-4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 14H4a2 2 0 01-2-2v-1a2 2 0 012-2h16a2 2 0 012 2v1a2 2 0 01-2 2h-2"/></svg>
    </a>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <p class="text-sm"><span class="text-gray-500">Description:</span> {{ $voucher->description ?: 'N/A' }}</p>
    </div>
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
            <tr>
                <th class="px-6 py-3">Account</th>
                <th class="px-6 py-3">Code</th>
                <th class="px-6 py-3 text-right">Debit</th>
                <th class="px-6 py-3 text-right">Credit</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm">
            @foreach($voucher->entries as $entry)
                <tr>
                    <td class="px-6 py-3">{{ $entry->account->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3">{{ $entry->account->code ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-right">{{ number_format($entry->debit, 2) }}</td>
                    <td class="px-6 py-3 text-right">{{ number_format($entry->credit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50 border-t">
            <tr>
                <td colspan="2" class="px-6 py-3 text-right font-semibold">Total</td>
                <td class="px-6 py-3 text-right font-semibold">{{ number_format($voucher->entries->sum('debit'), 2) }}</td>
                <td class="px-6 py-3 text-right font-semibold">{{ number_format($voucher->entries->sum('credit'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
