@extends('layouts.app')

@section('title', 'Expense Report')
@section('page-title', 'Expense Report')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between mb-4">
        <h2 class="text-lg font-semibold">Expense List</h2>
        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Export PDF
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Particulars</th>
                    <th class="p-3 border">date</th>
                    <th class="p-3 border text-right">Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach($expenses as $index=>$expense)
                <tr class="hover:bg-gray-50">
                <td class="p-3 border">{{ ++$index }}</td>
                <td class="p-3 border">{{ $expense->particulars }}</td>
                    <td class="p-3 border">{{ $expense->date }}</td>
                    <td class="p-3 border text-right text-red-600">
                        ৳ {{ number_format($expense->amount,2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection
