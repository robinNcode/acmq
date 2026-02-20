@extends('layouts.app')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow">

        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Sales List</h2>
            <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Export PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">

                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Customer</th>
                    <th class="p-3 border">Product</th>
                    <th class="p-3 border">Quantity</th>
                    <th class="p-3 border">Price</th>
                    <th class="p-3 border">Total</th>
                    <th class="p-3 border">Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration }}</td>
                        <td class="p-3 border">{{ $sale->customer->name }}</td>
                        <td class="p-3 border">{{ $sale->product->name }}</td>
                        <td class="p-3 border">{{ $sale->quantity }}</td>
                        <td class="p-3 border">{{ $sale->price }}</td>
                        <td class="p-3 border">{{ $sale->total }}</td>
                        <td class="p-3 border">{{ $sale->selling_date }}</td>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
