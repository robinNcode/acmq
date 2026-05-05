@extends('layouts.app')

@section('title', 'Vouchers')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Vouchers</h1>
        <p class="text-sm text-gray-500 mt-1">Receive, Payment and Journal vouchers.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('vouchers.create', ['type' => 'receive']) }}" class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm">New Receive</a>
        <a href="{{ route('vouchers.create', ['type' => 'payment']) }}" class="px-4 py-2 rounded-lg bg-amber-600 text-white text-sm">New Payment</a>
        <a href="{{ route('vouchers.create', ['type' => 'jv']) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm">New JV</a>
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
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Filter</button>
            <a href="{{ route('vouchers.index') }}" class="ml-2 text-gray-600">Clear</a>
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
                        <a href="{{ route('vouchers.show', $voucher->id) }}" class="text-indigo-600">View</a>
                        <a href="{{ route('vouchers.print', $voucher->id) }}" target="_blank" class="text-green-600 ml-3">Print</a>
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
