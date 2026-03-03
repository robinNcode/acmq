@extends('layouts.app')

@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="expenseCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Expense List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Expense
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
                    <th class="p-3 border">Branch</th>
                    <th class="p-3 border">Amount</th>
                    <th class="p-3 border">Particulars</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($expenses as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $expenses->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $e->branch->name ?? 'N/A' }}</td>
                        <td class="p-3 border text-right">৳ {{ number_format($e->amount, 2) }}</td>
                        <td class="p-3 border">{{ $e->particulars }}</td>
                        <td class="p-3 border">{{ $e->date->format('Y-m-d') }}</td>
                        <td class="p-3 border">
                            <button @click="openEditModal({{ json_encode($e) }})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No expenses found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $expenses->links() }}
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Expense' : 'Create New Expense'"></h3>
                </div>
                
                <form :action="isEdit ? '{{ url('expenses') }}/' + form.id : '{{ route('expenses.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Particulars (Description)</label>
                            <input type="text" name="particulars" x-model="form.particulars" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('particulars') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" step="0.01" name="amount" x-model="form.amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" x-model="form.date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
            Alpine.data('expenseCrud', () => ({
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
                form: {
                    id: '{{ old('id') ?? '' }}',
                    branch_id: '{{ old('branch_id') ?? '' }}',
                    amount: '{{ old('amount') ?? '' }}',
                    particulars: '{!! addslashes(old('particulars', '')) !!}',
                    date: '{!! addslashes(old('date', '')) !!}' || new Date().toISOString().substring(0,10)
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = { 
                        id: '', 
                        branch_id: '', 
                        amount: '', 
                        particulars: '', 
                        date: new Date().toISOString().substring(0,10)
                    };
                    this.showModal = true;
                },
                openEditModal(expense) {
                    this.isEdit = true;
                    this.form = {
                        id: expense.id,
                        branch_id: expense.branch_id,
                        amount: expense.amount,
                        particulars: expense.particulars,
                        date: expense.date ? new Date(expense.date).toISOString().substring(0,10) : ''
                    };
                    this.showModal = true;
                }
            }))
        })
    </script>
@endsection
