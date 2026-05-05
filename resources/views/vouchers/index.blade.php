@extends('layouts.app')

@section('title', 'Vouchers')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Vouchers</h1>
        <p class="text-sm text-gray-500 mt-1">Receive, Payment and Journal vouchers.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('vouchers.create', ['type' => 'receive']) }}" title="Create Receive Voucher" class="p-2 rounded-lg bg-green-600 text-white">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </a>
        <a href="{{ route('vouchers.create', ['type' => 'payment']) }}" title="Create Payment Voucher" class="p-2 rounded-lg bg-amber-600 text-white">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
        </a>
        <a href="{{ route('vouchers.create', ['type' => 'jv']) }}" title="Create Journal Voucher" class="p-2 rounded-lg bg-indigo-600 text-white">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
        </a>
    </div>
</div>

<div class="bg-white p-6 rounded-xl border shadow-sm mb-6">
    <form method="GET" action="{{ route('vouchers.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm text-gray-700 mb-1">Type</label>
            <select name="type" class="border rounded-lg px-3 py-2">
                <option value="">All</option>
                <option value="receive" {{ request('type') === 'receive' ? 'selected' : '' }}>Receive</option>
                <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Payment</option>
                <option value="jv" {{ request('type') === 'jv' ? 'selected' : '' }}>JV</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded-lg px-3 py-2">
        </div>
        <div>
            <button title="Apply filters" class="p-2 rounded-lg bg-indigo-600 text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 12h12m-9 8h6"/></svg>
            </button>
            <a href="{{ route('vouchers.index') }}" title="Clear filters" class="ml-2 inline-block p-2 rounded-lg bg-gray-200 text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
            <tr>
                <th class="px-6 py-3">Voucher</th>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Type</th>
                <th class="px-6 py-3">Branch</th>
                <th class="px-6 py-3 text-right">Amount</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm">
            @forelse($vouchers as $voucher)
                @php $amount = $voucher->entries->sum('debit'); @endphp
                <tr>
                    <td class="px-6 py-3">#{{ $voucher->id }}</td>
                    <td class="px-6 py-3">{{ $voucher->date }}</td>
                    <td class="px-6 py-3">{{ strtoupper($voucher->reference_type) }}</td>
                    <td class="px-6 py-3">{{ $voucher->branch->name ?? ('#' . $voucher->branch_id) }}</td>
                    <td class="px-6 py-3 text-right">{{ number_format($amount, 2) }}</td>
                    <td class="px-6 py-3 text-right">
                        <a href="{{ route('vouchers.show', $voucher->id) }}" title="View voucher" class="inline-block p-1 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('vouchers.print', $voucher->id) }}" target="_blank" title="Print voucher" class="inline-block p-1 text-green-600 ml-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18h12v4H6v-4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 14H4a2 2 0 01-2-2v-1a2 2 0 012-2h16a2 2 0 012 2v1a2 2 0 01-2 2h-2"/></svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">No vouchers found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($vouchers->hasPages())
        <div class="px-6 py-4 border-t">{{ $vouchers->links() }}</div>
    @endif
</div>
@endsection
