@extends('layouts.app')

@section('title', 'Voucher #' . $voucher->id)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ strtoupper($voucher->reference_type) }} Voucher #{{ $voucher->id }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $voucher->date }} | {{ $voucher->branch->name ?? ('#' . $voucher->branch_id) }}</p>
    </div>
    <div>
        <a target="_blank" href="{{ route('vouchers.print', $voucher->id) }}" class="px-4 py-2 rounded-lg bg-green-600 text-white">Print</a>
    </div>
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
