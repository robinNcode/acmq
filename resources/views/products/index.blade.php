@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="productCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Product List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Product
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Code</th>
                    <th class="p-3 border">Name</th>
                    <th class="p-3 border">Category</th>
                    <th class="p-3 border">Purchase Price</th>
                    <th class="p-3 border">Selling Price</th>
                    <th class="p-3 border">Stock</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $products->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $p->product_code }}</td>
                        <td class="p-3 border">{{ $p->name }}</td>
                        <td class="p-3 border">{{ $p->category ?? '-' }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($p->purchase_price, 2) }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($p->selling_price, 2) }}</td>
                        <td class="p-3 border text-center">{{ $p->stock_quantity }}</td>
                        <td class="p-3 border text-center">
                            <span class="px-2 py-1 rounded text-xs {{ $p->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($p->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="p-3 border">
                            <button @click="openEditModal({{ json_encode($p) }})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                            <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-4 text-center text-gray-500">No products found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Product' : 'Create New Product'"></h3>
                </div>
                <form :action="isEdit ? '{{ url('products') }}/' + form.id : '{{ route('products.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Product Code *</label>
                            <input type="text" name="product_code" x-model="form.product_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div class="mb-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <input type="text" name="name" x-model="form.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <input type="text" name="category" x-model="form.category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <input type="text" name="type" x-model="form.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <input type="text" name="unit" x-model="form.unit" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="e.g. pcs, kg, box">
                        </div>
                        <div class="mb-2 md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                            <input type="text" name="manufacturer" x-model="form.manufacturer" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Purchase Price *</label>
                            <input type="number" step="0.01" name="purchase_price" x-model="form.purchase_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Selling Price *</label>
                            <input type="number" step="0.01" name="selling_price" x-model="form.selling_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" x-model="form.stock_quantity" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" x-model="form.status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" x-model="form.description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" rows="2"></textarea>
                        </div>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('productCrud', () => ({
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: false,
                form: { id:'', product_code:'', name:'', category:'', type:'', unit:'', manufacturer:'', description:'', purchase_price:'0', selling_price:'0', stock_quantity:'0', status:'active' },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = { id:'', product_code:'', name:'', category:'', type:'', unit:'', manufacturer:'', description:'', purchase_price:'0', selling_price:'0', stock_quantity:'0', status:'active' };
                    this.showModal = true;
                },
                openEditModal(p) {
                    this.isEdit = true;
                    this.form = {
                        id: p.id, product_code: p.product_code, name: p.name, category: p.category || '',
                        type: p.type || '', unit: p.unit || '', manufacturer: p.manufacturer || '',
                        description: p.description || '', purchase_price: p.purchase_price,
                        selling_price: p.selling_price, stock_quantity: p.stock_quantity, status: p.status || 'active'
                    };
                    this.showModal = true;
                }
            }))
        })
    </script>
@endsection
