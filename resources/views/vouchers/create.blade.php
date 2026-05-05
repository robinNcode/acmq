@extends('layouts.app')

@section('title', 'Create Voucher')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Create {{ strtoupper($type) }} Voucher</h1>
</div>

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc ml-6">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('vouchers.store') }}" class="bg-white border rounded-xl shadow-sm p-6 space-y-5">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Branch</label>
            <select name="branch_id" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">Select Branch</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">Description</label>
            <input type="text" name="description" value="{{ old('description') }}" class="w-full border rounded-lg px-3 py-2">
        </div>
    </div>

    @if(in_array($type, ['receive', 'payment']))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm mb-1">Cash/Bank Account</label>
                <select name="cash_account_id" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('cash_account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Counterparty Account</label>
                <select name="counterparty_account_id" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('counterparty_account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Amount</label>
                <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>
    @else
        <div>
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold">JV Lines</h2>
                <button type="button" id="add-line" class="px-3 py-1 text-sm rounded bg-indigo-600 text-white">Add Line</button>
            </div>
            <div id="lines" class="space-y-2">
                @for($i=0; $i<3; $i++)
                    <div class="grid grid-cols-12 gap-2 line-row">
                        <div class="col-span-6">
                            <select name="lines[{{ $i }}][account_id]" class="w-full border rounded px-2 py-2">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" step="0.01" min="0" name="lines[{{ $i }}][debit]" placeholder="Debit" class="w-full border rounded px-2 py-2">
                        </div>
                        <div class="col-span-3">
                            <input type="number" step="0.01" min="0" name="lines[{{ $i }}][credit]" placeholder="Credit" class="w-full border rounded px-2 py-2">
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        <script>
            (function () {
                const lines = document.getElementById('lines');
                const addBtn = document.getElementById('add-line');
                if (!lines || !addBtn) return;
                let idx = lines.querySelectorAll('.line-row').length;
                const accountOptions = @json($accounts->map(function ($a) { return ['id' => $a->id, 'label' => $a->code . ' - ' . $a->name]; })->values());
                addBtn.addEventListener('click', function () {
                    const row = document.createElement('div');
                    row.className = 'grid grid-cols-12 gap-2 line-row';
                    const options = ['<option value="">Select Account</option>'].concat(
                        accountOptions.map(acc => `<option value="${acc.id}">${acc.label}</option>`)
                    ).join('');
                    row.innerHTML = `
                        <div class="col-span-6"><select name="lines[${idx}][account_id]" class="w-full border rounded px-2 py-2">${options}</select></div>
                        <div class="col-span-3"><input type="number" step="0.01" min="0" name="lines[${idx}][debit]" placeholder="Debit" class="w-full border rounded px-2 py-2"></div>
                        <div class="col-span-3"><input type="number" step="0.01" min="0" name="lines[${idx}][credit]" placeholder="Credit" class="w-full border rounded px-2 py-2"></div>`;
                    lines.appendChild(row);
                    idx++;
                });
            })();
        </script>
    @endif

    <div class="pt-2">
        <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white">Save Voucher</button>
        <a href="{{ route('vouchers.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </div>
</form>
@endsection
