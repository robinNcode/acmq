@extends('layouts.app')

@section('title', 'General Ledger')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">General Ledger</h1>
        <p class="text-sm text-gray-500 mt-1">View detailed accounting entries and transactions.</p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl border shadow-sm mb-6">
    <form method="GET" action="{{ route('reports.ledger-entries') }}" class="flex flex-wrap gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label for="account_id" class="block text-sm font-medium text-gray-700 mb-1">Account</label>
            <select name="account_id" id="account_id" class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 border outline-none">
                <option value="">All Accounts</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->code }} - {{ $acc->name }}
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
            <a href="{{ route('reports.ledger-entries') }}" class="ml-2 text-gray-600 hover:text-gray-900 px-4 py-2">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/75 border-b text-gray-500 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium">Description</th>
                <th class="px-6 py-4 font-medium">Account</th>
                <th class="px-6 py-4 font-medium text-right">Debit</th>
                <th class="px-6 py-4 font-medium text-right">Credit</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
            @forelse($entries as $entry)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">{{ $entry->journal->date ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $entry->journal->description ?? 'N/A' }}</td>
                <td class="px-6 py-4 font-medium text-indigo-700">{{ $entry->account->name ?? 'N/A' }} ({{ $entry->account->code ?? 'N/A' }})</td>
                <td class="px-6 py-4 text-right tabular-nums">{{ number_format($entry->debit, 2) }}</td>
                <td class="px-6 py-4 text-right tabular-nums">{{ number_format($entry->credit, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <p class="text-base font-medium">No journal entries found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($entries->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $entries->links() }}
    </div>
    @endif
</div>
@endsection
