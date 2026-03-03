@extends('layouts.app')

@section('title', 'Sales')
@section('page-title', 'Sales')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="saleCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Sales List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Sale
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
                    <th class="p-3 border">Customer</th>
                    <th class="p-3 border">Total Price</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $sales->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $s->code }}</td>
                        <td class="p-3 border">{{ $s->customer->name ?? 'N/A' }}</td>
                        <td class="p-3 border text-right">{{ number_format($s->total_price, 2) }}</td>
                        <td class="p-3 border">{{ $s->selling_date->format('Y-m-d H:i') }}</td>
                        <td class="p-3 border">
                            <button @click="openEditModal({{ json_encode($s) }})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                            <form action="{{ route('sales.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this sale?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No sales found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $sales->links() }}
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Sale' : 'Create New Sale'"></h3>
                </div>
                
                <form :action="isEdit ? '{{ url('sales') }}/' + form.id : '{{ route('sales.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Sale Code</label>
                            <input type="text" name="code" x-model="form.code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Selling Date</label>
                            <!-- Use x-model properly to load a formatted date -->
                            <input type="datetime-local" name="selling_date" x-model="form.selling_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('selling_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                            <label class="block text-sm font-medium text-gray-700">Customer</label>
                            <select name="customer_id" x-model="form.customer_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
            Alpine.data('saleCrud', () => ({
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
                form: {
                    id: '{{ old('id') ?? '' }}',
                    code: '{{ old('code') ?? '' }}',
                    branch_id: '{{ old('branch_id') ?? '' }}',
                    customer_id: '{{ old('customer_id') ?? '' }}',
                    selling_date: '{!! addslashes(old('selling_date', '')) !!}' || new Date().toISOString().substring(0,16),
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
                        customer_id: '', 
                        selling_date: new Date().toISOString().substring(0,16),
                        total_price: '0',
                        discount: '0',
                        paid: '0',
                        due: '0'
                    };
                    this.showModal = true;
                },
                openEditModal(sale) {
                    this.isEdit = true;
                    this.form = {
                        id: sale.id,
                        code: sale.code,
                        branch_id: sale.branch_id,
                        customer_id: sale.customer_id,
                        // Convert DB timestamp into datetime-local format
                        selling_date: sale.selling_date ? new Date(sale.selling_date).toISOString().substring(0,16) : '',
                        total_price: sale.total_price,
                        discount: sale.discount,
                        paid: sale.paid,
                        due: sale.due
                    };
                    this.showModal = true;
                }
            }))
        })
    </script>
@endsection
