@extends('layouts.app')

@section('title', 'Purchases')
@section('page-title', 'Purchases')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="purchaseCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Purchase List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Purchase
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                Check the form below for errors. Please try again.
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Code</th>
                    <th class="p-3 border">Supplier</th>
                    <th class="p-3 border">Total Price</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($purchases as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $purchases->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $p->code }}</td>
                        <td class="p-3 border">{{ $p->supplier->name ?? 'N/A' }}</td>
                        <td class="p-3 border text-right">{{ number_format($p->total_price, 2) }}</td>
                        <td class="p-3 border">{{ $p->purchase_date->format('Y-m-d H:i') }}</td>
                        <td class="p-3 border">
                            <button @click="openEditModal({{ json_encode($p) }})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                            <form action="{{ route('purchases.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No purchases found.
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

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Purchase' : 'Create New Purchase'"></h3>
                </div>
                
                <form :action="isEdit ? '{{ url('purchases') }}/' + form.id : '{{ route('purchases.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Purchase Code</label>
                            <input type="text" name="code" x-model="form.code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Purchase Date</label>
                            <input type="datetime-local" name="purchase_date" x-model="form.purchase_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('purchase_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Branch</label>
                            <select name="branch_id" x-model="form.branch_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" x-model="form.supplier_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Total Price</label>
                            <input type="number" step="0.01" name="total_price" x-model="form.total_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('total_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Discount</label>
                            <input type="number" step="0.01" name="discount" x-model="form.discount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('discount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Paid</label>
                            <input type="number" step="0.01" name="paid" x-model="form.paid" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('paid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Due</label>
                            <input type="number" step="0.01" name="due" x-model="form.due" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('due') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
            Alpine.data('purchaseCrud', () => ({
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
                form: {
                    id: '{{ old('id') ?? '' }}',
                    code: '{{ old('code') ?? '' }}',
                    branch_id: '{{ old('branch_id') ?? '' }}',
                    supplier_id: '{{ old('supplier_id') ?? '' }}',
                    purchase_date: '{!! addslashes(old('purchase_date', '')) !!}' || new Date().toISOString().substring(0,16),
                    total_price: '{{ old('total_price') ?? '0' }}',
                    discount: '{{ old('discount') ?? '0' }}',
                    paid: '{{ old('paid') ?? '0' }}',
                    due: '{{ old('due') ?? '0' }}'
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = { 
                        id: '', 
                        code: '', 
                        branch_id: '', 
                        supplier_id: '', 
                        purchase_date: new Date().toISOString().substring(0,16),
                        total_price: '0',
                        discount: '0',
                        paid: '0',
                        due: '0'
                    };
                    this.showModal = true;
                },
                openEditModal(purchase) {
                    this.isEdit = true;
                    this.form = {
                        id: purchase.id,
                        code: purchase.code,
                        branch_id: purchase.branch_id,
                        supplier_id: purchase.supplier_id,
                        purchase_date: purchase.purchase_date ? new Date(purchase.purchase_date).toISOString().substring(0,16) : '',
                        total_price: purchase.total_price,
                        discount: purchase.discount,
                        paid: purchase.paid,
                        due: purchase.due
                    };
                    this.showModal = true;
                }
            }))
        })
    </script>
@endsection
