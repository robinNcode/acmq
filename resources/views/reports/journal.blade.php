@extends('layouts.app')

@section('title', 'Journal Report')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Journal Report</h1>
        <p class="text-sm text-gray-500 mt-1">Review journal vouchers with debit and credit details.</p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl border shadow-sm mb-6">
    <form method="GET" action="{{ route('reports.journal') }}" class="flex flex-wrap gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
            <select name="branch_id" id="branch_id" class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border outline-none">
                <option value="">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-1/4">
            <label for="reference_type" class="block text-sm font-medium text-gray-700 mb-1">Reference Type</label>
            <select name="reference_type" id="reference_type" class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border outline-none">
                <option value="">All Types</option>
                @foreach($referenceTypes as $type)
                    <option value="{{ $type }}" {{ request('reference_type') === $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-1/4">
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border outline-none">
        </div>

        <div class="w-full md:w-1/4">
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border outline-none">
        </div>

        <div class="w-full md:w-auto">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow-sm font-medium transition-colors">
                Filter
            </button>
            <a href="{{ route('reports.journal') }}" class="ml-2 text-gray-600 hover:text-gray-900 px-4 py-2">Clear</a>
        </div>
    </form>
</div>

<div class="space-y-6">
    @forelse($journals as $journal)
        @php
            $totalDebit = $journal->entries->sum('debit');
            $totalCredit = $journal->entries->sum('credit');
            $isBalanced = bccomp((string) $totalDebit, (string) $totalCredit, 2) === 0;
        @endphp
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        JV#{{ $journal->id }} -
                        {{ \Illuminate\Support\Carbon::parse($journal->date)->format('Y-m-d') }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $journal->description ?: 'No description' }}
                    </p>
                </div>
                <div class="text-right text-sm">
                    <p><span class="text-gray-500">Branch:</span> {{ $journal->branch->name ?? ('#' . $journal->branch_id) }}</p>
                    <p><span class="text-gray-500">Ref:</span> {{ $journal->reference_type ?: 'N/A' }}{{ $journal->reference_id ? ' #' . $journal->reference_id : '' }}</p>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/75 border-b text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium">Account</th>
                        <th class="px-6 py-3 font-medium">Code</th>
                        <th class="px-6 py-3 font-medium text-right">Debit</th>
                        <th class="px-6 py-3 font-medium text-right">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
                    @foreach($journal->entries as $entry)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3">{{ $entry->account->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3">{{ $entry->account->code ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right tabular-nums">{{ number_format($entry->debit, 2) }}</td>
                            <td class="px-6 py-3 text-right tabular-nums">{{ number_format($entry->credit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-right font-semibold text-gray-900">Total</td>
                        <td class="px-6 py-3 text-right font-semibold tabular-nums">{{ number_format($totalDebit, 2) }}</td>
                        <td class="px-6 py-3 text-right font-semibold tabular-nums {{ $isBalanced ? '' : 'text-red-600' }}">{{ number_format($totalCredit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if(!$isBalanced)
                <div class="px-6 py-3 bg-red-50 text-red-700 text-sm border-t border-red-100">
                    Warning: This journal voucher is not balanced.
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border rounded-xl shadow-sm p-12 text-center text-gray-500">
            <p class="text-base font-medium">No journals found for the selected filters.</p>
        </div>
    @endforelse
</div>

@if($journals->hasPages())
    <div class="mt-6">
        {{ $journals->links() }}
    </div>
@endif
@endsection
