@extends('layouts.app')

@section('title', 'Branches')
@section('page-title', 'Branches')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow" x-data="branchCrud()">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-semibold">Branch List</h2>
            <button @click="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Branch
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
                    <th class="p-3 border">Branch Name</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">District</th>
                    <th class="p-3 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($branches as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">{{ $loop->iteration + $branches->firstItem() - 1 }}</td>
                        <td class="p-3 border">{{ $b->branch_code }}</td>
                        <td class="p-3 border">{{ $b->name }}</td>
                        <td class="p-3 border">{{ $b->address }}</td>
                        <td class="p-3 border">{{ $b->district }}</td>
                        <td class="p-3 border">
                            <button @click="openEditModal({{ json_encode($b) }})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                            <form action="{{ route('branches.destroy', $b->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $branches->links() }}
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50" style="display: none;">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-semibold" x-text="isEdit ? 'Edit Branch' : 'Create New Branch'"></h3>
                </div>
                
                <form :action="isEdit ? '{{ url('branches') }}/' + form.id : '{{ route('branches.store') }}'" method="POST" class="p-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Branch Name</label>
                            <input type="text" name="name" x-model="form.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2 relative">
                            <label class="block text-sm font-medium text-gray-700">Branch Code</label>
                            <input type="text" name="branch_code" x-model="form.branch_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('branch_code') <span class="text-red-500 text-xs text-wrap absolute">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">District</label>
                            <input type="text" name="district" x-model="form.district" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('district') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Thana</label>
                            <input type="text" name="thana" x-model="form.thana" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            @error('thana') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" name="postal_code" x-model="form.postal_code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-2 md:col-span-2 mt-4 md:mt-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" x-model="form.address" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" rows="2"></textarea>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
            Alpine.data('branchCrud', () => ({
                showModal: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
                form: {
                    id: '{{ old('id') ?? '' }}',
                    branch_code: '{{ old('branch_code') ?? '' }}',
                    name: '{!! addslashes(old('name') ?? '') !!}',
                    thana: '{!! addslashes(old('thana') ?? '') !!}',
                    district: '{!! addslashes(old('district') ?? '') !!}',
                    postal_code: '{{ old('postal_code') ?? '' }}',
                    address: '{!! addslashes(old('address') ?? '') !!}'
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = { id: '', branch_code: '', name: '', thana: '', district: '', postal_code: '', address: '' };
                    this.showModal = true;
                },
                openEditModal(branch) {
                    this.isEdit = true;
                    this.form = {
                        id: branch.id,
                        branch_code: branch.branch_code,
                        name: branch.name,
                        thana: branch.thana,
                        district: branch.district,
                        postal_code: branch.postal_code,
                        address: branch.address
                    };
                    this.showModal = true;
                }
            }))
        })
    </script>
@endsection
