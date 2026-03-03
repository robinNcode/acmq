@extends('layouts.app')

@section('title', 'Sales')
@section('page-title', 'Sales')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="saleCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Sales List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + New Sale
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Code</th>
                    <th class="p-3 border">Customer</th>
                    <th class="p-3 border">Branch</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Total</th>
                    <th class="p-3 border">Paid</th>
                    <th class="p-3 border">Due</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $sales->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $s->code }}</td>
                        <td class="p-3 border">{{ $s->customer->name ?? '-' }}</td>
                        <td class="p-3 border">{{ $s->branch->name ?? '-' }}</td>
                        <td class="p-3 border">{{ $s->selling_date?->format('d M Y') }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($s->total_price, 2) }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($s->paid, 2) }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($s->due, 2) }}</td>
                        <td class="p-3 border whitespace-nowrap">
                            <button @click="openEditModal({{ json_encode($s) }})" class="text-blue-500 hover:text-blue-700 mr-1">Edit</button>
                            <a href="{{ route('sales.invoice', $s->id) }}" class="text-green-600 hover:text-green-800 mr-1" target="_blank">Invoice</a>
                            <form action="{{ route('sales.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sale?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-4 text-center text-gray-500">No sales found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $sales->links() }}</div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Sale' : 'Create New Sale'"></h3>
                </div>
                <form :action="isEdit ? '{{ url('sales') }}/' + form.id : '{{ route('sales.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Code *</label>
                            <input type="text" name="code" x-model="form.code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Branch *</label>
                            <select name="branch_id" x-model="form.branch_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer *</label>
                            <select name="customer_id" x-model="form.customer_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date *</label>
                            <input type="date" name="selling_date" x-model="form.selling_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                    </div>

                    <!-- Product Items -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold text-gray-700">Products</h4>
                            <button type="button" @click="addItem()" class="text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">+ Add Row</button>
                        </div>
                        <table class="min-w-full border text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="p-2 border">Product</th>
                                <th class="p-2 border w-24">Qty</th>
                                <th class="p-2 border w-32">Unit Price</th>
                                <th class="p-2 border w-32">Subtotal</th>
                                <th class="p-2 border w-16"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(item, index) in form.items" :key="index">
                                <tr>
                                    <td class="p-2 border">
                                        <select :name="'items['+index+'][product_id]'" x-model="item.product_id" class="w-full border border-gray-300 rounded p-1" required @change="onProductSelect(index)">
                                            <option value="">Select</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">{{ $p->name }} ({{ $p->product_code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2 border">
                                        <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" min="1" class="w-full border border-gray-300 rounded p-1 text-right" @input="calcRow(index)" required>
                                    </td>
                                    <td class="p-2 border">
                                        <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" class="w-full border border-gray-300 rounded p-1 text-right" @input="calcRow(index)" required>
                                    </td>
                                    <td class="p-2 border text-right font-medium" x-text="(item.quantity * item.unit_price).toFixed(2)"></td>
                                    <td class="p-2 border text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700" x-show="form.items.length > 1">&times;</button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                            <tfoot>
                            <tr class="bg-gray-50 font-semibold">
                                <td colspan="3" class="p-2 border text-right">Subtotal</td>
                                <td class="p-2 border text-right" x-text="'৳ ' + subtotal().toFixed(2)"></td>
                                <td class="p-2 border"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Discount</label>
                            <input type="number" step="0.01" name="discount" x-model.number="form.discount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Paid</label>
                            <input type="number" step="0.01" name="paid" x-model.number="form.paid" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Due</label>
                            <input type="number" step="0.01" name="due" x-model.number="form.due" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded p-3 mt-4 text-right font-semibold text-lg">
                        Grand Total: ৳ <span x-text="grandTotal().toFixed(2)"></span>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" @click="showModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" x-text="isEdit ? 'Update' : 'Save'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productPrices = {!! json_encode($products->pluck('selling_price', 'id')) !!};

        document.addEventListener('alpine:init', () => {
            Alpine.data('saleCrud', () => ({
                showModal: false,
                isEdit: false,
                form: {
                    id: '', code: '', branch_id: '', customer_id: '', selling_date: '',
                    discount: 0, paid: 0, due: 0,
                    items: [{ product_id: '', quantity: 1, unit_price: 0 }]
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = {
                        id: '', code: '', branch_id: '', customer_id: '', selling_date: '',
                        discount: 0, paid: 0, due: 0,
                        items: [{ product_id: '', quantity: 1, unit_price: 0 }]
                    };
                    this.showModal = true;
                },
                openEditModal(s) {
                    this.isEdit = true;
                    let items = (s.product_info && s.product_info.length)
                        ? s.product_info.map(i => ({
                            product_id: i.product_id ?? '', quantity: parseInt(i.quantity) || 1,
                            unit_price: parseFloat(i.unit_price) || 0
                        }))
                        : [{ product_id: '', quantity: 1, unit_price: 0 }];
                    this.form = {
                        id: s.id, code: s.code, branch_id: s.branch_id, customer_id: s.customer_id,
                        selling_date: s.selling_date ? s.selling_date.substring(0, 10) : '',
                        discount: parseFloat(s.discount) || 0, paid: parseFloat(s.paid) || 0, due: parseFloat(s.due) || 0,
                        items: items
                    };
                    this.showModal = true;
                },
                addItem() {
                    this.form.items.push({ product_id: '', quantity: 1, unit_price: 0 });
                },
                removeItem(index) {
                    this.form.items.splice(index, 1);
                },
                onProductSelect(index) {
                    const pid = this.form.items[index].product_id;
                    if (pid && productPrices[pid]) {
                        this.form.items[index].unit_price = parseFloat(productPrices[pid]);
                    }
                },
                calcRow(index) {},
                subtotal() {
                    return this.form.items.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
                },
                grandTotal() {
                    return this.subtotal() - this.form.discount;
                }
            }))
        })
    </script>
@endsection
