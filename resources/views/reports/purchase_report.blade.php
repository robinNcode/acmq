@extends('layouts.app')

@section('title', 'Purchase Report')
@section('page-title', 'Purchase Report')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow">

        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Purchase List</h2>
            <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Export PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">

                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Supplier</th>
                    <th class="p-3 border">Product</th>
                    <th class="p-3 border">Qty</th>
                    <th class="p-3 border">Unit Price</th>
                    <th class="p-3 border">Line Total</th>
                    <th class="p-3 border">Purchase Total</th>
                    <th class="p-3 border">Date</th>
                </tr>
                </thead>

                <tbody>
                @forelse($purchases as $purchase)
                    @foreach($purchase->product_info as $index => $product)
                        <tr class="hover:bg-gray-50">
                            @if($index === 0)
                                <td class="p-3 border" rowspan="{{ count($purchase->product_info) }}">
                                    {{ $purchases->firstItem() + $loop->parent->index }}
                                </td>

                                <td class="p-3 border" rowspan="{{ count($purchase->product_info) }}">
                                    {{ $purchase->supplier->name ?? 'N/A' }}
                                </td>
                            @endif

                            <td class="p-3 border">
                                {{ $product['name'] }}
                            </td>

                            <td class="p-3 border text-center">
                                {{ $product['quantity'] }}
                            </td>

                            <td class="p-3 border text-right">
                                {{ number_format($product['price'], 2) }}
                            </td>

                            <td class="p-3 border text-right">
                                {{ number_format($product['quantity'] * $product['price'], 2) }}
                            </td>

                            @if($index === 0)
                                <td class="p-3 border text-right font-semibold"
                                    rowspan="{{ count($purchase->product_info) }}">
                                    {{ number_format($purchase->total_price, 2) }}
                                </td>

                                <td class="p-3 border"
                                    rowspan="{{ count($purchase->product_info) }}">
                                    {{ $purchase->purchase_date->format('Y-m-d H:i') }}
                                </td>
                            @endif

                        </tr>
                    @endforeach

                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">
                            No sales found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $purchases->links() }}
        </div>

    </div>
@endsection
